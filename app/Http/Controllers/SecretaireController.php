<?php

namespace App\Http\Controllers;

use App\Models\{Enseignant, Cours, Activite, Ressource, Sequence, AnneeAcademique,
                Grade, Statut, Departement, Semestre, Specialite, TypeActivite,
                TypeRessource, Utilisateur};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecretaireController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────
    public function dashboard()
    {
        $annee = AnneeAcademique::encours();
        $stats = [
            'total_enseignants' => Enseignant::count(),
            'total_cours'       => Cours::count(),
            'total_activites'   => Activite::when($annee, fn($q) => $q->where('id_anee', $annee->id_anee))->count(),
            'volume_total'      => Activite::when($annee, fn($q) => $q->where('id_anee', $annee->id_anee))->sum('v_hor'),
            'annee'             => $annee,
        ];
        $enseignants = Enseignant::with(['grade','statut','departement','utilisateur'])
            ->withSum(['activites as volume_horaire' => fn($q) => $annee ? $q->where('id_anee',$annee->id_anee) : $q], 'v_hor')
            ->orderByDesc('volume_horaire')
            ->paginate(10);
        return view('secretaire.dashboard', compact('stats','enseignants','annee'));
    }

    // ── Enseignants ───────────────────────────────────────────
    public function enseignants()
    {
        $enseignants = Enseignant::with(['grade','statut','departement','utilisateur'])
            ->withSum('activites as volume_horaire','v_hor')
            ->paginate(15);
        return view('secretaire.enseignants', compact('enseignants'));
    }

    public function createEnseignant()
    {
        return view('secretaire.enseignants-form', [
            'enseignant'   => null,
            'grades'       => Grade::all(),
            'statuts'      => Statut::all(),
            'departements' => Departement::all(),
        ]);
    }

    public function storeEnseignant(Request $request)
    {
        $data = $request->validate([
            'nom'                             => 'required|string|max:100',
            'pnom'                            => 'required|string|max:100',
            'tel'                             => 'nullable|string|max:20',
            'tx_horaire'                      => 'required|numeric|min:0',
            'id_grd'                          => 'required|exists:grades,id_grd',
            'id_stat'                         => 'required|exists:statuts,id_stat',
            'id_dep'                          => 'required|exists:departements,id_dep',
            'email_compte'                    => 'nullable|email|unique:utilisateurs,email',
            'password_compte'                 => 'nullable|min:8|confirmed',
            'password_compte_confirmation'    => 'nullable',
        ]);

        $idUtil = null;
        if (!empty($data['email_compte']) && !empty($data['password_compte'])) {
            $baseLogin = strtolower(
                preg_replace('/[^a-z0-9]/i','', $data['pnom']) . '.' .
                preg_replace('/[^a-z0-9]/i','', $data['nom'])
            );
            $login = $baseLogin;
            $i = 1;
            while (Utilisateur::where('login', $login)->exists()) {
                $login = $baseLogin . $i++;
            }
            $user   = Utilisateur::create([
                'login' => $login,
                'email' => $data['email_compte'],
                'mdp'   => Hash::make($data['password_compte']),
                'role'  => 'enseignant',
            ]);
            $idUtil = $user->id_util;
        }

        Enseignant::create([
            'nom'        => $data['nom'],
            'pnom'       => $data['pnom'],
            'tel'        => $data['tel'] ?? null,
            'tx_horaire' => $data['tx_horaire'],
            'id_grd'     => $data['id_grd'],
            'id_stat'    => $data['id_stat'],
            'id_dep'     => $data['id_dep'],
            'id_util'    => $idUtil,
        ]);

        return redirect()->route('secretaire.enseignants')->with('success','Enseignant ajouté.');
    }

    public function editEnseignant(Enseignant $enseignant)
    {
        return view('secretaire.enseignants-form', [
            'enseignant'   => $enseignant,
            'grades'       => Grade::all(),
            'statuts'      => Statut::all(),
            'departements' => Departement::all(),
        ]);
    }

    public function updateEnseignant(Request $request, Enseignant $enseignant)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:100',
            'pnom'       => 'required|string|max:100',
            'tel'        => 'nullable|string|max:20',
            'tx_horaire' => 'required|numeric|min:0',
            'id_grd'     => 'required|exists:grades,id_grd',
            'id_stat'    => 'required|exists:statuts,id_stat',
            'id_dep'     => 'required|exists:departements,id_dep',
        ]);
        $enseignant->update($data);
        return redirect()->route('secretaire.enseignants')->with('success','Enseignant mis à jour.');
    }

    public function destroyEnseignant(Enseignant $enseignant)
    {
        $enseignant->delete();
        return redirect()->route('secretaire.enseignants')->with('success','Enseignant supprimé.');
    }

    // ── Cours ─────────────────────────────────────────────────
    public function cours()
    {
        $cours       = Cours::with(['semestre','specialite','sequences'])->paginate(15);
        $semestres   = Semestre::all();
        $specialites = Specialite::all();
        return view('secretaire.cours', compact('cours','semestres','specialites'));
    }

    public function storeCours(Request $request)
    {
        $data = $request->validate([
            'intit'     => 'required|string|max:200',
            'filre'     => 'required|string|max:150',
            'niv'       => 'required|in:L1,L2,L3,M1,M2',
            'nbh_bse'   => 'required|numeric|min:0',
            'nbr_crdt'  => 'required|integer|min:1',
            'nbr_squce' => 'required|integer|min:1',
            'id_sem'    => 'required|exists:semestres,id_sem',
            'id_spec'   => 'required|exists:specialites,id_spec',
        ]);
        Cours::create($data);
        return redirect()->route('secretaire.cours')->with('success','Cours créé.');
    }

    // ── Séquences ─────────────────────────────────────────────
    public function sequences(Cours $cours)
    {
        $sequences = $cours->sequences()->withCount('ressources')->get();
        return view('secretaire.sequences', compact('cours','sequences'));
    }

    public function storeSequence(Request $request, Cours $cours)
    {
        $data = $request->validate([
            'ttre_seq' => 'required|string|max:200',
            'desc_seq' => 'nullable|string',
        ]);
        $ordre = $cours->sequences()->max('ordre') + 1;
        Sequence::create([
            'ttre_seq' => $data['ttre_seq'],
            'desc_seq' => $data['desc_seq'] ?? null,
            'id_crs'   => $cours->id_crs,
            'ordre'    => $ordre,
        ]);
        return redirect()->route('secretaire.sequences', $cours)->with('success','Séquence ajoutée.');
    }

    public function destroySequence(Sequence $sequence)
    {
        $cours = $sequence->cours;
        $sequence->delete();
        return redirect()->route('secretaire.sequences', $cours)->with('success','Séquence supprimée.');
    }

    // ── Ressources ────────────────────────────────────────────
    public function ressources(Sequence $sequence)
    {
        $ressources      = $sequence->ressources()->with('typeRessource')->get();
        $typesRessources = TypeRessource::all();
        return view('secretaire.ressources', compact('sequence','ressources','typesRessources'));
    }

    public function storeRessource(Request $request, Sequence $sequence)
    {
        $data = $request->validate([
            'niv_comp'       => 'required|in:1,2,3',
            'id_typ_ress'    => 'required|exists:types_ressources,id_typ_ress',
            'dte_creat_ress' => 'required|date',
        ]);
        Ressource::create([
            'niv_comp'       => $data['niv_comp'],
            'id_typ_ress'    => $data['id_typ_ress'],
            'dte_creat_ress' => $data['dte_creat_ress'],
            'id_seq'         => $sequence->id_seq,
        ]);
        return redirect()->route('secretaire.ressources', $sequence)->with('success','Ressource ajoutée.');
    }

    public function destroyRessource(Ressource $ressource)
    {
        $sequence = $ressource->sequence;
        $ressource->delete();
        return redirect()->route('secretaire.ressources', $sequence)->with('success','Ressource supprimée.');
    }

    // ── Activités ─────────────────────────────────────────────
    public function activites()
    {
        $annee     = AnneeAcademique::encours();
        $activites = Activite::with(['enseignant','typeActivite','ressource.sequence.cours','annee'])
            ->when($annee, fn($q) => $q->where('id_anee', $annee->id_anee))
            ->latest('date_act')
            ->paginate(20);
        return view('secretaire.activites', compact('activites','annee'));
    }

    public function createActivite()
    {
        return view('secretaire.activites-form', [
            'activite'      => null,
            'enseignants'   => Enseignant::orderBy('nom')->get(),
            'ressources'    => Ressource::with('sequence.cours')->get(),
            'typesActivites'=> TypeActivite::all(),
            'annees'        => AnneeAcademique::all(),
        ]);
    }

    public function storeActivite(Request $request)
    {
        $data = $request->validate([
            'date_act'   => 'required|date',
            'id_ens'     => 'required|exists:enseignants,id_ens',
            'id_anee'    => 'required|exists:annees_academiques,id_anee',
            'id_typ_act' => 'required|exists:types_activites,id_typ_act',
            'id_ress'    => 'nullable|exists:ressources,id_ress',
            'observation'=> 'nullable|string',
        ]);
        Activite::create($data);
        return redirect()->route('secretaire.activites')->with('success','Activité enregistrée.');
    }
}
