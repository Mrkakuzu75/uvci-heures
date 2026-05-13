<?php

namespace App\Services;

use App\Models\Activite;
use App\Models\AnneeAcademique;
use App\Models\Enseignant;
use App\Models\TypeActivite;
use Illuminate\Support\Collection;

/**
 * SERVICE MÉTIER : Calcul des volumes horaires
 *
 * Implémente la grille du cahier des charges UVCI.
 * Ce service est le point central de tout calcul horaire.
 * À injecter dans les controllers via le constructeur.
 */
class VolumeHoraireService
{
    // ──────────────────────────────────────────────
    // GRILLE OFFICIELLE (cahier des charges)
    // ──────────────────────────────────────────────
    const GRILLE = [
        TypeActivite::CREATION => [
            1 => 0.40,
            2 => 0.75,
            3 => 1.50,
        ],
        TypeActivite::MISE_A_JOUR => [
            1 => 0.20,
            2 => 0.375,
            3 => 0.75,
        ],
    ];

    /**
     * Calcule le volume horaire d'une activité.
     *
     * @param int $idTypAct  TypeActivite::CREATION ou MISE_A_JOUR
     * @param int $nivComp   Niveau de complexité (1, 2 ou 3)
     * @param int $nbrSquce  Nombre de séquences du cours concerné
     * @return float         Volume horaire en heures
     */
    public function calculer(int $idTypAct, int $nivComp, int $nbrSquce): float
    {
        $coeff = self::GRILLE[$idTypAct][$nivComp] ?? 0.0;
        return round($coeff * $nbrSquce, 2);
    }

    /**
     * Recalcule et sauvegarde le v_hor d'une activité existante.
     */
    public function recalculerActivite(Activite $activite): float
    {
        $ressource = $activite->ressource()->with('sequence.cours')->first();

        if (! $ressource) {
            return 0.0;
        }

        $vHor = $this->calculer(
            $activite->id_typ_act,
            $ressource->niv_comp,
            $ressource->sequence->cours->nbr_squce ?? 1
        );

        $activite->update(['v_hor' => $vHor]);

        return $vHor;
    }

    /**
     * Volume horaire total d'un enseignant pour une année.
     */
    public function totalParEnseignant(Enseignant $enseignant, int $idAnee): float
    {
        return $enseignant->activites()
                          ->where('id_anee', $idAnee)
                          ->sum('v_hor');
    }

    /**
     * Récapitulatif complet d'un enseignant pour une année.
     * Retourne un tableau avec : total, créations, mises_à_jour, montant
     */
    public function recapitulatif(Enseignant $enseignant, int $idAnee): array
    {
        $activites = $enseignant->activites()
                                ->where('id_anee', $idAnee)
                                ->with('typeActivite')
                                ->get();

        $totalCreation   = $activites->where('id_typ_act', TypeActivite::CREATION)->sum('v_hor');
        $totalMaj        = $activites->where('id_typ_act', TypeActivite::MISE_A_JOUR)->sum('v_hor');
        $totalHeures     = $totalCreation + $totalMaj;
        $montant         = $totalHeures * $enseignant->tx_horaire;

        return [
            'enseignant'       => $enseignant,
            'annee_id'         => $idAnee,
            'heures_creation'  => round($totalCreation, 2),
            'heures_maj'       => round($totalMaj, 2),
            'total_heures'     => round($totalHeures, 2),
            'taux_horaire'     => $enseignant->tx_horaire,
            'montant_total'    => round($montant, 2),
            'nb_activites'     => $activites->count(),
        ];
    }

    /**
     * Tableau de bord global pour une année : tous les enseignants.
     * Retourne une Collection triée par volume horaire décroissant.
     */
    public function tableauDeBord(int $idAnee): Collection
    {
        return Enseignant::with(['grade', 'statut', 'departement'])
            ->get()
            ->map(fn($ens) => $this->recapitulatif($ens, $idAnee))
            ->sortByDesc('total_heures')
            ->values();
    }

    /**
     * Retourne le coefficient pour un type et un niveau donné.
     * Utile dans les vues pour afficher la grille.
     */
    public function coefficient(int $idTypAct, int $nivComp): float
    {
        return self::GRILLE[$idTypAct][$nivComp] ?? 0.0;
    }
}
