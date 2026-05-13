<?php

namespace App\Http\Controllers;

use App\Models\{Activite, AnneeAcademique};
use Illuminate\Support\Facades\Auth;

class EnseignantController extends Controller
{
    private function enseignant()
    {
        $user = Auth::user();

        // Si l'enseignant n'est pas lié au compte → rediriger avec message clair
        if (!$user->enseignant) {
            abort(403, 'Votre compte n\'est pas encore lié à un profil enseignant. Contactez le secrétaire.');
        }

        return $user->enseignant;
    }

    public function dashboard()
    {
        $enseignant           = $this->enseignant();
        $annee                = AnneeAcademique::encours();

        $activites = Activite::with(['typeActivite', 'ressource.sequence.cours'])
            ->where('id_ens', $enseignant->id_ens)
            ->when($annee, fn($q) => $q->where('id_anee', $annee->id_anee))
            ->latest('date_act')
            ->take(10)
            ->get();

        $volumeTotal           = $enseignant->volumeHoraireTotal($annee?->id_anee);
        $heuresComplementaires = $enseignant->heuresComplementaires(192, $annee?->id_anee);

        return view('enseignant.dashboard', compact(
            'enseignant', 'annee', 'activites',
            'volumeTotal', 'heuresComplementaires'
        ));
    }

    public function activites()
    {
        $enseignant = $this->enseignant();
        $annees     = AnneeAcademique::orderByDesc('dte_dbut')->get();
        $anneeId    = request('annee_id', AnneeAcademique::encours()?->id_anee);

        $activites = Activite::with(['typeActivite', 'ressource.sequence.cours', 'annee'])
            ->where('id_ens', $enseignant->id_ens)
            ->when($anneeId, fn($q) => $q->where('id_anee', $anneeId))
            ->latest('date_act')
            ->paginate(20);

        $volumeTotal = Activite::where('id_ens', $enseignant->id_ens)
            ->when($anneeId, fn($q) => $q->where('id_anee', $anneeId))
            ->sum('v_hor');

        return view('enseignant.activites', compact(
            'activites', 'annees', 'anneeId', 'volumeTotal', 'enseignant'
        ));
    }
}
