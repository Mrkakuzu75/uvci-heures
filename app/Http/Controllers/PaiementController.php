<?php

namespace App\Http\Controllers;

use App\Models\{Enseignant, Activite, AnneeAcademique};
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────
    private function getAnnee(?int $anneeId = null): ?AnneeAcademique
    {
        if ($anneeId) return AnneeAcademique::find($anneeId);
        return AnneeAcademique::encours();
    }

    private function buildEtat(AnneeAcademique $annee): array
    {
        return Enseignant::with(['grade','statut','departement'])
            ->get()
            ->map(function (Enseignant $ens) use ($annee) {
                $activites = $ens->activites()
                    ->with(['typeActivite','ressource.sequence.cours'])
                    ->where('id_anee', $annee->id_anee)
                    ->latest('date_act')
                    ->get();

                $volumeTotal           = (float) $activites->sum('v_hor');
                $heuresNormales        = min($volumeTotal, 192);
                $heuresComplementaires = max(0, $volumeTotal - 192);
                $montantNormal         = $heuresNormales * (float) $ens->tx_horaire;
                $montantCompl          = $heuresComplementaires * (float) $ens->tx_horaire * 1.5;
                $montantTotal          = $montantNormal + $montantCompl;

                return [
                    'enseignant'            => $ens,
                    'activites'             => $activites,
                    'volume_total'          => $volumeTotal,
                    'heures_normales'       => $heuresNormales,
                    'heures_complementaires'=> $heuresComplementaires,
                    'montant_normal'        => $montantNormal,
                    'montant_complementaire'=> $montantCompl,
                    'montant_total'         => $montantTotal,
                ];
            })
            ->sortByDesc('volume_total')
            ->values()
            ->toArray();
    }

    // ── État global des paiements (Secrétaire) ────────────────
    public function etatPaiements(Request $request)
    {
        $anneeId = $request->input('annee_id');
        $annee   = $this->getAnnee($anneeId);
        $annees  = AnneeAcademique::orderByDesc('dte_dbut')->get();

        if (!$annee) {
            return view('secretaire.paiements', [
                'etat'   => [],
                'annee'  => null,
                'annees' => $annees,
                'totaux' => ['volume'=>0,'montant'=>0,'nb_ens'=>0],
            ]);
        }

        $etat = $this->buildEtat($annee);

        $totaux = [
            'volume'  => collect($etat)->sum('volume_total'),
            'montant' => collect($etat)->sum('montant_total'),
            'nb_ens'  => collect($etat)->filter(fn($e) => $e['volume_total'] > 0)->count(),
        ];

        return view('secretaire.paiements', compact('etat','annee','annees','totaux'));
    }

    // ── PDF état global (Secrétaire) ──────────────────────────
    public function pdfEtatGlobal(Request $request)
    {
        $anneeId = $request->input('annee_id');
        $annee   = $this->getAnnee($anneeId);

        if (!$annee) abort(404, 'Année non trouvée');

        $etat   = $this->buildEtat($annee);
        $totaux = [
            'volume'  => collect($etat)->sum('volume_total'),
            'montant' => collect($etat)->sum('montant_total'),
            'nb_ens'  => collect($etat)->filter(fn($e) => $e['volume_total'] > 0)->count(),
        ];

        return view('pdf.etat-global', compact('etat','annee','totaux'));
    }

    // ── Fiche individuelle PDF (Secrétaire ou Enseignant) ─────
    public function ficheIndividuelle(Request $request, Enseignant $enseignant)
    {
        $anneeId = $request->input('annee_id');
        $annee   = $this->getAnnee($anneeId);
        $annees  = AnneeAcademique::orderByDesc('dte_dbut')->get();

        if (!$annee) abort(404, 'Année non trouvée');

        $activites = $enseignant->activites()
            ->with(['typeActivite','ressource.sequence.cours'])
            ->where('id_anee', $annee->id_anee)
            ->latest('date_act')
            ->get();

        $volumeTotal            = (float) $activites->sum('v_hor');
        $heuresComplementaires  = max(0, $volumeTotal - 192);
        $montantTotal           = $volumeTotal * (float) $enseignant->tx_horaire;
        $montantCompl           = $heuresComplementaires * (float) $enseignant->tx_horaire * 0.5;

        return view('pdf.fiche-individuelle', compact(
            'enseignant','annee','annees','activites',
            'volumeTotal','heuresComplementaires','montantTotal','montantCompl'
        ));
    }

    // ── Récapitulatif enseignant connecté ─────────────────────
    public function recapitulatifEnseignant(Request $request)
    {
        $user       = auth()->user();
        $enseignant = $user->enseignant;

        if (!$enseignant) abort(403);

        $anneeId = $request->input('annee_id');
        $annee   = $this->getAnnee($anneeId);
        $annees  = AnneeAcademique::orderByDesc('dte_dbut')->get();

        if (!$annee) abort(404);

        $activites = $enseignant->activites()
            ->with(['typeActivite','ressource.sequence.cours'])
            ->where('id_anee', $annee->id_anee)
            ->latest('date_act')
            ->get();

        $volumeTotal           = (float) $activites->sum('v_hor');
        $heuresComplementaires = max(0, $volumeTotal - 192);
        $montantTotal          = $volumeTotal * (float) $enseignant->tx_horaire;
        $montantCompl          = $heuresComplementaires * (float) $enseignant->tx_horaire * 0.5;

        return view('pdf.fiche-individuelle', compact(
            'enseignant','annee','annees','activites',
            'volumeTotal','heuresComplementaires','montantTotal','montantCompl'
        ));
    }
}
