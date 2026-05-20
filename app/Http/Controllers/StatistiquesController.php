<?php

namespace App\Http\Controllers;

use App\Models\{Activite, Enseignant, Departement, AnneeAcademique};
use App\Services\ExcelExporter;

class StatistiquesController extends Controller
{
    // ── Helper : collecter toutes les stats ───────────────────
    private function buildStats(AnneeAcademique $annee): array
    {
        // 1. Répartition par type d'activité
        $types = Activite::query()
            ->where('id_anee', $annee->id_anee)
            ->join('types_activites', 'activites.id_typ_act', '=', 'types_activites.id_typ_act')
            ->selectRaw('types_activites.lib_typ_act,
                         COUNT(*) as nb_activites,
                         ROUND(SUM(activites.v_hor), 2) as volume_total')
            ->groupBy('types_activites.id_typ_act', 'types_activites.lib_typ_act')
            ->get();

        // 2. Répartition par niveau de complexité
        $niveaux = Activite::query()
            ->where('id_anee', $annee->id_anee)
            ->join('ressources', 'activites.id_ress', '=', 'ressources.id_ress')
            ->selectRaw('ressources.niv_comp,
                         COUNT(*) as nb_activites,
                         ROUND(SUM(activites.v_hor), 2) as volume_total')
            ->groupBy('ressources.niv_comp')
            ->orderBy('ressources.niv_comp')
            ->get();

        // 3. Répartition par type de ressource
        $ressources = Activite::query()
            ->where('id_anee', $annee->id_anee)
            ->join('ressources',       'activites.id_ress',      '=', 'ressources.id_ress')
            ->join('types_ressources', 'ressources.id_typ_ress', '=', 'types_ressources.id_typ_ress')
            ->selectRaw('types_ressources.lib_typ_ress,
                         COUNT(*) as nb_activites,
                         ROUND(SUM(activites.v_hor), 2) as volume_total')
            ->groupBy('types_ressources.lib_typ_ress')
            ->orderByDesc('volume_total')
            ->get();

        // 4. Volume par département
        $departements = Departement::leftJoin('enseignants', 'departements.id_dep', '=', 'enseignants.id_dep')
            ->leftJoin('activites', function($join) use ($annee) {
                $join->on('enseignants.id_ens', '=', 'activites.id_ens')
                     ->where('activites.id_anee', '=', $annee->id_anee);
            })
            ->selectRaw('departements.lib_dep,
                         COUNT(DISTINCT enseignants.id_ens) as nb_enseignants,
                         COUNT(activites.id_act) as nb_activites,
                         ROUND(COALESCE(SUM(activites.v_hor), 0), 2) as volume_total')
            ->groupBy('departements.id_dep', 'departements.lib_dep')
            ->orderByDesc('volume_total')
            ->get();

        // 5. Enseignants ayant dépassé leur charge
        $config  = AdminController::loadConfig();
        $seuil   = $config['seuil_heures_complementaires'] ?? 192;

        $depasses = Enseignant::with(['grade','statut','departement'])
            ->withSum(['activites as volume_horaire' => fn($q) => $q->where('id_anee', $annee->id_anee)], 'v_hor')
            ->having('volume_horaire', '>', $seuil)
            ->orderByDesc('volume_horaire')
            ->get();

        // 6. Statistiques mensuelles
        $moisNoms = ['Janvier','Février','Mars','Avril','Mai','Juin',
                     'Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
        $mensuelles = collect(range(1,12))->map(fn($m) => [
            'mois'         => $m,
            'nom'          => $moisNoms[$m-1],
            'nb_activites' => Activite::where('id_anee', $annee->id_anee)->whereMonth('date_act',$m)->count(),
            'volume'       => round((float) Activite::where('id_anee', $annee->id_anee)->whereMonth('date_act',$m)->sum('v_hor'), 2),
        ]);

        return compact('types','niveaux','ressources','departements','depasses','mensuelles','seuil');
    }

    // ── PDF rapport statistiques ──────────────────────────────
    public function pdf(\Illuminate\Http\Request $request)
    {
        $anneeId = $request->input('annee_id');
        $annee   = $anneeId ? AnneeAcademique::find($anneeId) : AnneeAcademique::encours();
        if (!$annee) abort(404, 'Année non trouvée');

        $stats = $this->buildStats($annee);
        $date  = now()->format('d/m/Y à H:i');

        return view('pdf.statistiques', compact('annee','date','stats'));
    }

    // ── Excel rapport statistiques ────────────────────────────
    public function excel(\Illuminate\Http\Request $request)
    {
        $anneeId = $request->input('annee_id');
        $annee   = $anneeId ? AnneeAcademique::find($anneeId) : AnneeAcademique::encours();
        if (!$annee) abort(404);

        $stats = $this->buildStats($annee);

        $exporter = new ExcelExporter('Statistiques '.$annee->lib_anee);

        // Feuille 1 : Répartition par type
        $totalVol = $stats['types']->sum('volume_total') ?: 1;
        $exporter->addSheet('Par type d\'activité',
            ['Type d\'activité', 'Nb activités', 'Volume (h)', 'Part (%)'],
            $stats['types']->map(fn($t) => [
                $t->lib_typ_act,
                $t->nb_activites,
                (float)$t->volume_total,
                round($t->volume_total / $totalVol * 100, 1),
            ])->toArray()
        );

        // Feuille 2 : Répartition par niveau
        $totalNiv = $stats['niveaux']->sum('volume_total') ?: 1;
        $exporter->addSheet('Par niveau de complexité',
            ['Niveau', 'Description', 'Nb activités', 'Volume (h)', 'Part (%)'],
            $stats['niveaux']->map(fn($n) => [
                'Niveau '.$n->niv_comp,
                match((int)$n->niv_comp) {1=>'Contenus simples + quiz', 2=>'Niv.1 + interactifs', 3=>'Serious games', default=>'—'},
                $n->nb_activites,
                (float)$n->volume_total,
                round($n->volume_total / $totalNiv * 100, 1),
            ])->toArray()
        );

        // Feuille 3 : Par département
        $totalDep = $stats['departements']->sum('volume_total') ?: 1;
        $exporter->addSheet('Par département',
            ['Département', 'Enseignants', 'Activités', 'Volume (h)', 'Part (%)'],
            $stats['departements']->map(fn($d) => [
                $d->lib_dep,
                $d->nb_enseignants,
                $d->nb_activites,
                (float)$d->volume_total,
                round($d->volume_total / $totalDep * 100, 1),
            ])->toArray()
        );

        // Feuille 4 : Statistiques mensuelles
        $exporter->addSheet('Statistiques mensuelles',
            ['Mois', 'Nb activités', 'Volume (h)'],
            $stats['mensuelles']->map(fn($m) => [
                $m['nom'],
                $m['nb_activites'],
                (float)$m['volume'],
            ])->toArray()
        );

        // Feuille 5 : Enseignants dépassés
        if ($stats['depasses']->isNotEmpty()) {
            $exporter->addSheet('Dépassements de charge',
                ['Enseignant', 'Grade', 'Département', 'Volume total (h)', 'H. complémentaires', 'Seuil (h)'],
                $stats['depasses']->map(fn($e) => [
                    $e->nom_complet,
                    $e->grade?->lib_grd ?? '—',
                    $e->departement?->lib_dep ?? '—',
                    round((float)$e->volume_horaire, 2),
                    round(max(0, (float)$e->volume_horaire - $stats['seuil']), 2),
                    $stats['seuil'],
                ])->toArray()
            );
        }

        $filename = 'statistiques-'.$annee->lib_anee.'-'.now()->format('Ymd').'.xlsx';
        $exporter->download($filename);
    }
}
