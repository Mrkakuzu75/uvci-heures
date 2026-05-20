<?php

namespace App\Http\Controllers;

use App\Models\{Enseignant, Activite, AnneeAcademique};
use App\Services\ExcelExporter;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────
    private function getAnnee(?int $id = null): ?AnneeAcademique
    {
        return $id ? AnneeAcademique::find($id) : AnneeAcademique::encours();
    }

    private function buildLigne(Enseignant $ens, AnneeAcademique $annee): array
    {
        $activites             = $ens->activites()
            ->with(['typeActivite','ressource.sequence.cours'])
            ->where('id_anee', $annee->id_anee)
            ->latest('date_act')->get();

        $volumeTotal           = (float) $activites->sum('v_hor');
        $heuresNormales        = min($volumeTotal, 192);
        $heuresCompl           = max(0, $volumeTotal - 192);
        $montantNormal         = $heuresNormales * (float) $ens->tx_horaire;
        $montantCompl          = $heuresCompl * (float) $ens->tx_horaire * 1.5;

        return [
            'enseignant'             => $ens,
            'activites'              => $activites,
            'volume_total'           => $volumeTotal,
            'heures_normales'        => $heuresNormales,
            'heures_complementaires' => $heuresCompl,
            'montant_normal'         => $montantNormal,
            'montant_complementaire' => $montantCompl,
            'montant_total'          => $montantNormal + $montantCompl,
        ];
    }

    private function buildEtat(AnneeAcademique $annee): array
    {
        return Enseignant::with(['grade','statut','departement'])
            ->get()
            ->map(fn($ens) => $this->buildLigne($ens, $annee))
            ->sortByDesc('volume_total')
            ->values()
            ->toArray();
    }

    private function totaux(array $etat): array
    {
        return [
            'volume'  => collect($etat)->sum('volume_total'),
            'montant' => collect($etat)->sum('montant_total'),
            'nb_ens'  => collect($etat)->filter(fn($e) => $e['volume_total'] > 0)->count(),
        ];
    }

    // ── Vue état paiements ────────────────────────────────────
    public function etatPaiements(Request $request)
    {
        $annee  = $this->getAnnee($request->input('annee_id'));
        $annees = AnneeAcademique::orderByDesc('dte_dbut')->get();

        if (!$annee) {
            return view('secretaire.paiements', [
                'etat'=>[],'annee'=>null,'annees'=>$annees,
                'totaux'=>['volume'=>0,'montant'=>0,'nb_ens'=>0],
            ]);
        }

        $etat   = $this->buildEtat($annee);
        $totaux = $this->totaux($etat);
        return view('secretaire.paiements', compact('etat','annee','annees','totaux'));
    }

    // ── PDF état global ───────────────────────────────────────
    public function pdfEtatGlobal(Request $request)
    {
        $annee = $this->getAnnee($request->input('annee_id'));
        if (!$annee) abort(404);
        $etat   = $this->buildEtat($annee);
        $totaux = $this->totaux($etat);
        $date   = now()->format('d/m/Y à H:i');
        return view('pdf.etat-global', compact('etat','annee','totaux','date'));
    }

    // ── Excel état global ─────────────────────────────────────
    public function excelEtatGlobal(Request $request)
    {
        $annee = $this->getAnnee($request->input('annee_id'));
        if (!$annee) abort(404);

        $etat   = $this->buildEtat($annee);
        $totaux = $this->totaux($etat);

        $headers = [
            'N°','Nom','Prénom','Grade','Statut','Département',
            'Taux (FCFA/h)','Vol. total (h)','H. normales','H. complémentaires',
            'Mt. normal (FCFA)','Mt. complémentaire (FCFA)','MONTANT TOTAL (FCFA)',
        ];

        $rows = [];
        foreach ($etat as $idx => $ligne) {
            $ens    = $ligne['enseignant'];
            $rows[] = [
                $idx + 1,
                strtoupper($ens->nom),
                $ens->pnom,
                $ens->grade?->lib_grd ?? '',
                $ens->statut?->lib_stat ?? '',
                $ens->departement?->lib_dep ?? '',
                (float) $ens->tx_horaire,
                round($ligne['volume_total'], 2),
                round($ligne['heures_normales'], 2),
                round($ligne['heures_complementaires'], 2),
                round($ligne['montant_normal']),
                round($ligne['montant_complementaire']),
                round($ligne['montant_total']),
            ];
        }
        $rows[] = [
            '__total' => true,
            'values'  => [
                '', 'TOTAL GÉNÉRAL', '', '', '', '', '',
                round($totaux['volume'], 2),
                '', '', '', '',
                round($totaux['montant']),
            ],
        ];

        (new ExcelExporter)
            ->addSheet('État de paiement '.$annee->lib_anee, $headers, $rows)
            ->download('etat-paiement-'.$annee->lib_anee.'-'.now()->format('Ymd').'.xlsx');
    }

    // ── PDF fiche individuelle enseignant ─────────────────────
    public function ficheIndividuelle(Request $request, Enseignant $enseignant)
    {
        $annee  = $this->getAnnee($request->input('annee_id'));
        $annees = AnneeAcademique::orderByDesc('dte_dbut')->get();
        if (!$annee) abort(404);

        $ligne              = $this->buildLigne($enseignant, $annee);
        $activites          = $ligne['activites'];
        $volumeTotal        = $ligne['volume_total'];
        $heuresComplementaires = $ligne['heures_complementaires'];
        $montantNormal      = $ligne['montant_normal'];
        $montantCompl       = $ligne['montant_complementaire'];
        $montantTotal       = $ligne['montant_total'];
        $date               = now()->format('d/m/Y à H:i');

        return view('pdf.fiche-individuelle', compact(
            'enseignant','annee','annees','activites','date',
            'volumeTotal','heuresComplementaires','montantNormal','montantCompl','montantTotal'
        ));
    }

    // ── Excel fiche individuelle enseignant (SECRÉTAIRE) ──────
    public function excelFicheIndividuelle(Request $request, Enseignant $enseignant)
    {
        $annee = $this->getAnnee($request->input('annee_id'));
        if (!$annee) abort(404);

        $ligne     = $this->buildLigne($enseignant, $annee);
        $activites = $ligne['activites'];

        // ── Feuille 1 : résumé ───────────────────────────────
        $headersResume = ['Champ', 'Valeur'];
        $rowsResume = [
            ['Nom complet',              strtoupper($enseignant->nom).' '.$enseignant->pnom],
            ['Grade',                    $enseignant->grade?->lib_grd ?? '—'],
            ['Statut',                   $enseignant->statut?->lib_stat ?? '—'],
            ['Département',              $enseignant->departement?->lib_dep ?? '—'],
            ['Taux horaire (FCFA/h)',    (float) $enseignant->tx_horaire],
            ['Année académique',         $annee->lib_anee],
            ['Volume horaire total (h)', round($ligne['volume_total'], 2)],
            ['Heures normales (h)',      round($ligne['heures_normales'], 2)],
            ['Heures complémentaires (h)', round($ligne['heures_complementaires'], 2)],
            ['Montant normal (FCFA)',    round($ligne['montant_normal'])],
            ['Montant complémentaire (FCFA)', round($ligne['montant_complementaire'])],
            ['MONTANT TOTAL (FCFA)',     round($ligne['montant_total'])],
        ];
        // Ligne total en vert
        $rowsResume[] = [
            '__total' => true,
            'values'  => ['MONTANT TOTAL À PAYER (FCFA)', round($ligne['montant_total'])],
        ];

        // ── Feuille 2 : détail activités ─────────────────────
        $headersAct = [
            'Date','Cours','Séquence',
            'Type d\'activité','Niveau complexité','Volume (h)',
        ];
        $rowsAct = [];
        foreach ($activites as $act) {
            $rowsAct[] = [
                $act->date_act->format('d/m/Y'),
                $act->ressource?->sequence?->cours?->intit ?? '—',
                $act->ressource?->sequence?->ttre_seq ?? '—',
                $act->typeActivite?->lib_typ_act ?? '—',
                'Niveau ' . ($act->ressource?->niv_comp ?? '?'),
                round($act->v_hor, 2),
            ];
        }
        $rowsAct[] = [
            '__total' => true,
            'values'  => ['', '', '', '', 'TOTAL', round($ligne['volume_total'], 2)],
        ];

        $nomFichier = strtolower(
            preg_replace('/[^a-z0-9]/i', '-', $enseignant->pnom) . '-' .
            preg_replace('/[^a-z0-9]/i', '-', $enseignant->nom)
        );

        (new ExcelExporter)
            ->addSheet('Résumé', $headersResume, $rowsResume)
            ->addSheet('Détail activités', $headersAct, $rowsAct)
            ->download('fiche-'.$nomFichier.'-'.$annee->lib_anee.'-'.now()->format('Ymd').'.xlsx');
    }

    // ── Récapitulatif PDF enseignant connecté ─────────────────
    public function recapitulatifEnseignant(Request $request)
    {
        $enseignant = auth()->user()?->enseignant;
        if (!$enseignant) abort(403);

        $annee  = $this->getAnnee($request->input('annee_id'));
        $annees = AnneeAcademique::orderByDesc('dte_dbut')->get();
        if (!$annee) abort(404);

        $ligne              = $this->buildLigne($enseignant, $annee);
        $activites          = $ligne['activites'];
        $volumeTotal        = $ligne['volume_total'];
        $heuresComplementaires = $ligne['heures_complementaires'];
        $montantNormal      = $ligne['montant_normal'];
        $montantCompl       = $ligne['montant_complementaire'];
        $montantTotal       = $ligne['montant_total'];
        $date               = now()->format('d/m/Y à H:i');

        return view('pdf.fiche-individuelle', compact(
            'enseignant','annee','annees','activites','date',
            'volumeTotal','heuresComplementaires','montantNormal','montantCompl','montantTotal'
        ));
    }

    // ── Excel récapitulatif enseignant connecté ───────────────
    public function excelRecapitulatif(Request $request)
    {
        $enseignant = auth()->user()?->enseignant;
        if (!$enseignant) abort(403);
        return $this->excelFicheIndividuelle($request, $enseignant);
    }
}
