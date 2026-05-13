<?php

namespace App\Http\Controllers;

use App\Models\{Enseignant, AnneeAcademique};
use App\Services\ExcelExporter;

class ExportEnseignantsController extends Controller
{
    public function excel()
    {
        $annee       = AnneeAcademique::encours();
        $enseignants = Enseignant::with([
            'grade', 'statut', 'departement', 'utilisateur', 'activites'
        ])->orderBy('nom')->get();

        // ── En-têtes ──────────────────────────────────────────
        $headers = [
            'N°',
            'Nom',
            'Prénom',
            'Grade',
            'Statut',
            'Département',
            'Téléphone',
            'Email',
            'Taux horaire (FCFA/h)',
            'Volume horaire total (h)',
            'Heures complémentaires (h)',
        ];

        // ── Lignes ─────────────────────────────────────────────
        $rows = [];
        foreach ($enseignants as $idx => $ens) {
            $volumeTotal = (float) $ens->activites
                ->when($annee, fn($c) => $c->where('id_anee', $annee->id_anee))
                ->sum('v_hor');
            $heuresCompl = max(0, $volumeTotal - 192);
            $montant     = min($volumeTotal, 192) * (float) $ens->tx_horaire
                         + $heuresCompl * (float) $ens->tx_horaire * 1.5;

            $rows[] = [
                $idx + 1,
                strtoupper($ens->nom),
                $ens->pnom,
                $ens->grade?->lib_grd    ?? '—',
                $ens->statut?->lib_stat  ?? '—',
                $ens->departement?->lib_dep ?? '—',
                $ens->tel ?? '—',
                $ens->utilisateur?->email ?? '—',
                (float) $ens->tx_horaire,
                round($volumeTotal, 2),
                round($heuresCompl, 2),
                round($montant),
            ];
        }

        // ── Ligne total ────────────────────────────────────────
      
        $filename = 'enseignants-uvci-' . ($annee?->lib_anee ?? now()->year) . '-' . now()->format('Ymd') . '.xlsx';

        $exporter = new ExcelExporter('Enseignants UVCI');
        $exporter->addSheet('Liste des enseignants', $headers, $rows);
        $exporter->download($filename);
    }
}
