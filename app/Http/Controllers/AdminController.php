<?php

namespace App\Http\Controllers;

use App\Models\{Utilisateur, Enseignant, Activite, AnneeAcademique, Departement, Grade, Statut};
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────
    public function dashboard()
    {
        $annee = AnneeAcademique::encours();
        $stats = [
            'total_enseignants'  => Enseignant::count(),
            'total_utilisateurs' => Utilisateur::count(),
            'total_activites'    => Activite::when($annee, fn($q) => $q->where('id_anee', $annee->id_anee))->count(),
            'volume_total'       => Activite::when($annee, fn($q) => $q->where('id_anee', $annee->id_anee))->sum('v_hor'),
            'annee'              => $annee,
        ];
        $enseignantsActifs = Enseignant::with(['grade','statut','departement'])
            ->withSum(['activites as volume_horaire' => fn($q) => $annee ? $q->where('id_anee',$annee->id_anee) : $q], 'v_hor')
            ->orderByDesc('volume_horaire')->take(5)->get();
        $utilisateurs = Utilisateur::with('enseignant')->latest()->take(10)->get();
        return view('admin.dashboard', compact('stats','enseignantsActifs','utilisateurs'));
    }

    // ── Utilisateurs ──────────────────────────────────────────
    public function utilisateurs()
    {
        $utilisateurs = Utilisateur::with('enseignant')->latest()->paginate(15);
        return view('admin.utilisateurs', compact('utilisateurs'));
    }

    public function createUtilisateur()
    {
        return view('admin.utilisateurs-form', ['utilisateur'=>null,'grades'=>Grade::all(),'statuts'=>Statut::all(),'departements'=>Departement::all()]);
    }

    public function storeUtilisateur(Request $request)
    {
        $data = $request->validate([
            'login'    => 'required|string|max:100|unique:utilisateurs,login',
            'email'    => 'required|email|max:150|unique:utilisateurs,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:administrateur,secretaire,enseignant',
        ]);
        Utilisateur::create(['login'=>$data['login'],'email'=>$data['email'],'mdp'=>bcrypt($data['password']),'role'=>$data['role']]);
        return redirect()->route('admin.utilisateurs')->with('success','Utilisateur créé.');
    }

    public function editUtilisateur(Utilisateur $utilisateur)
    {
        return view('admin.utilisateurs-form', ['utilisateur'=>$utilisateur,'grades'=>Grade::all(),'statuts'=>Statut::all(),'departements'=>Departement::all()]);
    }

    public function updateUtilisateur(Request $request, Utilisateur $utilisateur)
    {
        $rules = [
            'login' => 'required|string|max:100|unique:utilisateurs,login,'.$utilisateur->id_util.',id_util',
            'email' => 'required|email|max:150|unique:utilisateurs,email,'.$utilisateur->id_util.',id_util',
            'role'  => 'required|in:administrateur,secretaire,enseignant',
        ];
        if ($request->filled('password')) $rules['password'] = 'string|min:8|confirmed';
        $data = $request->validate($rules);
        $utilisateur->update([
            'login'=>$data['login'],'email'=>$data['email'],'role'=>$data['role'],
            ...($request->filled('password') ? ['mdp'=>bcrypt($data['password'])] : []),
        ]);
        return redirect()->route('admin.utilisateurs')->with('success','Compte mis à jour.');
    }

    public function destroyUtilisateur(Utilisateur $utilisateur)
    {
        if ($utilisateur->id_util === auth()->id()) {
            return redirect()->route('admin.utilisateurs')->with('error','Impossible de supprimer votre propre compte.');
        }
        $utilisateur->delete();
        return redirect()->route('admin.utilisateurs')->with('success','Compte supprimé.');
    }

    // ── Années académiques ────────────────────────────────────
    public function annees()
    {
        $annees = AnneeAcademique::latest()->get();
        return view('admin.annees', compact('annees'));
    }

    public function storeAnnee(Request $request)
    {
        $data = $request->validate([
            'lib_anee'  => 'required|string|max:20',
            'dte_dbut'  => 'required|date',
            'dte_fn'    => 'required|date|after:dte_dbut',
            'etat_anee' => 'required|in:en_cours,cloturee,a_venir',
        ]);
        if ($data['etat_anee'] === 'en_cours') {
            AnneeAcademique::where('etat_anee','en_cours')->update(['etat_anee'=>'cloturee']);
        }
        AnneeAcademique::create($data);
        return redirect()->route('admin.annees')->with('success','Année créée.');
    }

    // ── Paramètres de calcul ──────────────────────────────────
    public function parametres()
    {
        $config = $this->loadConfig();
        return view('admin.parametres', [
            'coefficients' => $config['coefficients'],
            'seuil'        => $config['seuil_heures_complementaires'],
        ]);
    }

    public function updateParametres(Request $request)
    {
        $data = $request->validate([
            'creation_niv1' => 'required|numeric|min:0|max:10',
            'creation_niv2' => 'required|numeric|min:0|max:10',
            'creation_niv3' => 'required|numeric|min:0|max:10',
            'maj_niv1'      => 'required|numeric|min:0|max:10',
            'maj_niv2'      => 'required|numeric|min:0|max:10',
            'maj_niv3'      => 'required|numeric|min:0|max:10',
            'seuil'         => 'required|numeric|min:1|max:5000',
        ]);

        $config = [
            'coefficients' => [
                1 => [1=>(float)$data['creation_niv1'], 2=>(float)$data['creation_niv2'], 3=>(float)$data['creation_niv3']],
                2 => [1=>(float)$data['maj_niv1'],      2=>(float)$data['maj_niv2'],      3=>(float)$data['maj_niv3']],
            ],
            'seuil_heures_complementaires' => (float)$data['seuil'],
        ];
        file_put_contents(storage_path('app/uvci_config.json'), json_encode($config, JSON_PRETTY_PRINT));

        return redirect()->route('admin.parametres')->with('success','Paramètres de calcul mis à jour.');
    }

    // ── Taux horaires ─────────────────────────────────────────
    public function tauxHoraires()
    {
        $enseignants = Enseignant::with(['grade','statut','departement'])->orderBy('nom')->get();
        return view('admin.taux-horaires', compact('enseignants'));
    }

    public function updateTaux(Request $request, Enseignant $enseignant)
    {
        $data = $request->validate(['tx_horaire' => 'required|numeric|min:0']);
        $enseignant->update($data);
        return redirect()->route('admin.taux-horaires')
                         ->with('success', 'Taux de '.$enseignant->nom_complet.' mis à jour.');
    }

    // ── Helper : charger config JSON ──────────────────────────
    public static function loadConfig(): array
    {
        $path = storage_path('app/uvci_config.json');
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            // Recast keys to int
            if (isset($data['coefficients'])) {
                $cast = [];
                foreach ($data['coefficients'] as $typeId => $niveaux) {
                    $castNiv = [];
                    foreach ($niveaux as $niv => $val) $castNiv[(int)$niv] = (float)$val;
                    $cast[(int)$typeId] = $castNiv;
                }
                $data['coefficients'] = $cast;
            }
            return $data;
        }
        // Valeurs par défaut du cahier des charges
        return [
            'coefficients' => [
                1 => [1 => 0.40, 2 => 0.75, 3 => 1.50],
                2 => [1 => 0.20, 2 => 0.375, 3 => 0.75],
            ],
            'seuil_heures_complementaires' => 192,
        ];
    }
}
