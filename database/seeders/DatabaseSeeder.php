<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{
    Utilisateur, Grade, Statut, Departement,
    Specialite, Semestre, TypeRessource, TypeActivite,
    AnneeAcademique, Enseignant, Cours, Sequence, Ressource, Activite
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. GRADES
        foreach (['Assistant', 'Maître-Assistant', 'Professeur'] as $g) {
            Grade::firstOrCreate(['lib_grd' => $g]);
        }

        // 2. STATUTS
        Statut::firstOrCreate(['lib_stat' => 'Permanent']);
        Statut::firstOrCreate(['lib_stat' => 'Vacataire']);

        // 3. DEPARTEMENTS
        $depts = [];
        foreach ([
            'Département Informatique et Sciences du Numérique',
            'Département Communication et Multimédia',
            'Département Marketing Digital',
            'Département Entrepreneuriat Numérique',
            'Département Sciences des Données',
        ] as $lib) {
            $depts[] = Departement::firstOrCreate(['lib_dep' => $lib]);
        }

        // 4. SPECIALITES
        $specsData = [
            'DAS' => 'DAS — Développeur d\'Applications et e-Services',
            'RSI' => 'RSI — Réseaux et Sécurité Informatique',
            'BD'  => 'BD — Base de Données',
            'CM'  => 'CM — Communication et Multimédia',
            'MD'  => 'MD — Marketing Digital',
            'ENT' => 'ENT — Entrepreneuriat Digital',
        ];
        $specs = [];
        foreach ($specsData as $code => $lib) {
            $specs[$code] = Specialite::firstOrCreate(['lib_spec' => $lib]);
        }

        // 5. SEMESTRES
        $semestresData = [
            'S1L1' => 'Semestre 1 — L1',
            'S2L1' => 'Semestre 2 — L1',
            'S3L2' => 'Semestre 3 — L2',
            'S4L2' => 'Semestre 4 — L2',
        ];
        $sems = [];
        foreach ($semestresData as $code => $lib) {
            $sems[$code] = Semestre::firstOrCreate(['lib_sem' => $lib]);
        }

        // 6. TYPES DE RESSOURCES
        $typRessData = [
            'TXT'  => 'Contenu textuel',
            'VID'  => 'Vidéo pédagogique',
            'PDF'  => 'Document PDF',
            'QUIZ' => 'Quiz',
        ];
        $typRess = [];
        foreach ($typRessData as $code => $lib) {
            $typRess[$code] = TypeRessource::firstOrCreate(['lib_typ_ress' => $lib]);
        }

        // 7. TYPES D'ACTIVITES
        TypeActivite::firstOrCreate(['id_typ_act' => 1], ['lib_typ_act' => 'Creation de ressource']);
        TypeActivite::firstOrCreate(['id_typ_act' => 2], ['lib_typ_act' => 'Mise a jour de ressource']);

        // 8. ANNEES ACADEMIQUES
        AnneeAcademique::firstOrCreate(['lib_anee' => '2024-2025'], [
            'dte_dbut' => '2024-10-01', 'dte_fn' => '2025-07-31', 'etat_anee' => 'cloturee',
        ]);
        AnneeAcademique::firstOrCreate(['lib_anee' => '2025-2026'], [
            'dte_dbut' => '2025-10-01', 'dte_fn' => '2026-07-31', 'etat_anee' => 'en_cours',
        ]);
        $annee = AnneeAcademique::where('etat_anee', 'en_cours')->first();

        // 9. UTILISATEURS
        Utilisateur::firstOrCreate(['email' => 'admin@uvci.edu.ci'], [
            'login' => 'admin', 'mdp' => Hash::make('Admin@2026'), 'role' => 'administrateur',
        ]);
        Utilisateur::firstOrCreate(['email' => 'secretaire@uvci.edu.ci'], [
            'login' => 'secretaire', 'mdp' => Hash::make('Secret@2026'), 'role' => 'secretaire',
        ]);

        // 5 enseignants -> 1 par departement
        $ensUsersData = [
            ['email'=>'konan.kouassi@uvci.edu.ci',   'login'=>'konan.kouassi',   'nom'=>'KOUASSI',   'pnom'=>'Konan Eric',    'departement'=>'Informatique'],
            ['email'=>'aya.coulibaly@uvci.edu.ci',    'login'=>'aya.coulibaly',   'nom'=>'COULIBALY', 'pnom'=>'Aya Marie',     'departement'=>'Marketing'],
            ['email'=>'joel.koffi@uvci.edu.ci',       'login'=>'joel.koffi',      'nom'=>'KOFFI',     'pnom'=>'Joel Arnaud',  'departement'=>'Communication'],
            ['email'=>'brou.yao@uvci.edu.ci',         'login'=>'brou.yao',        'nom'=>'YAO',       'pnom'=>'Brou Theodore','departement'=>'Donnees'],
            ['email'=>'mariam.traore@uvci.edu.ci',    'login'=>'mariam.traore',   'nom'=>'TRAORE',    'pnom'=>'Mariam',       'departement'=>'Entrepreneuriat'],
        ];

        $gProf = Grade::where('lib_grd', 'Professeur')->first();
        $gMA = Grade::where('lib_grd', 'Maitre-Assistant')->first();
        $gAsst = Grade::where('lib_grd', 'Assistant')->first();
        $sPerm = Statut::where('lib_stat', 'Permanent')->first();
        $sVaca = Statut::where('lib_stat', 'Vacataire')->first();

        $enseignantUsers = [];
        foreach ($ensUsersData as $eu) {
            $enseignantUsers[] = Utilisateur::firstOrCreate(
                ['email' => $eu['email']],
                ['login' => $eu['login'], 'mdp' => Hash::make('Enseignant@2026'), 'role' => 'enseignant']
            );
        }

        $enseignantsData = [
            ['nom'=>'KOUASSI', 'pnom'=>'Konan Eric', 'tel'=>'0701020304', 'tx_horaire'=>7500, 'id_grd'=>$gProf->id_grd, 'id_stat'=>$sPerm->id_stat, 'departement_nom'=>'Département Informatique et Sciences du Numérique', 'id_util'=>$enseignantUsers[0]->id_util],
            ['nom'=>'COULIBALY', 'pnom'=>'Aya Marie', 'tel'=>'0505060708', 'tx_horaire'=>5000, 'id_grd'=>$gMA->id_grd, 'id_stat'=>$sPerm->id_stat, 'departement_nom'=>'Département Marketing Digital', 'id_util'=>$enseignantUsers[1]->id_util],
            ['nom'=>'KOFFI', 'pnom'=>'Joel Arnaud', 'tel'=>'0709101112', 'tx_horaire'=>4000, 'id_grd'=>$gAsst->id_grd, 'id_stat'=>$sVaca->id_stat, 'departement_nom'=>'Département Communication et Multimédia', 'id_util'=>$enseignantUsers[2]->id_util],
            ['nom'=>'YAO', 'pnom'=>'Brou Theodore', 'tel'=>'0712131415', 'tx_horaire'=>4500, 'id_grd'=>$gAsst->id_grd, 'id_stat'=>$sVaca->id_stat, 'departement_nom'=>'Département Sciences des Données', 'id_util'=>$enseignantUsers[3]->id_util],
            ['nom'=>'TRAORE', 'pnom'=>'Mariam', 'tel'=>'0716171819', 'tx_horaire'=>6000, 'id_grd'=>$gMA->id_grd, 'id_stat'=>$sPerm->id_stat, 'departement_nom'=>'Département Entrepreneuriat Numérique', 'id_util'=>$enseignantUsers[4]->id_util],
        ];

        $enseignants = [];
        foreach ($enseignantsData as $ed) {
            $dep = Departement::where('lib_dep', $ed['departement_nom'])->first();
            if ($dep) {
                $enseignants[] = Enseignant::firstOrCreate(
                    ['id_util' => $ed['id_util']],
                    [
                        'nom' => $ed['nom'],
                        'pnom' => $ed['pnom'],
                        'tel' => $ed['tel'],
                        'tx_horaire' => $ed['tx_horaire'],
                        'id_grd' => $ed['id_grd'],
                        'id_stat' => $ed['id_stat'],
                        'id_dep' => $dep->id_dep,
                        'id_util' => $ed['id_util'],
                    ]
                );
            }
        }

        // 10. COURS + SEQUENCES + RESSOURCES
        $coursData = [
            [
                'intit'=>'Introduction à la Programmation','filre'=>'DAS','niv'=>'L1',
                'nbh_bse'=>10,'nbr_crdt'=>2,'nbr_squce'=>40,
                'id_sem'=>$sems['S1L1']->id_sem,'id_spec'=>$specs['DAS']->id_spec,
                'sequences' => [
                    ['ttre'=>'Bases du langage Python','desc'=>'Variables, types, operateurs'],
                    ['ttre'=>'Structures de controle','desc'=>'If, while, for'],
                ],
            ],
            [
                'intit'=>'Marketing Digital','filre'=>'MD','niv'=>'L2',
                'nbh_bse'=>20,'nbr_crdt'=>3,'nbr_squce'=>80,
                'id_sem'=>$sems['S3L2']->id_sem,'id_spec'=>$specs['MD']->id_spec,
                'sequences' => [
                    ['ttre'=>'Strategie digitale','desc'=>'Positionnement et cibles'],
                    ['ttre'=>'SEO et referencement','desc'=>'Optimisation pour les moteurs'],
                ],
            ],
        ];

        $typRessKeys = ['TXT','VID','QUIZ','PDF'];

        foreach ($coursData as $cd) {
            $seqs = $cd['sequences'];
            unset($cd['sequences']);

            $cours = Cours::firstOrCreate(
                ['intit' => $cd['intit'], 'id_sem' => $cd['id_sem']],
                $cd
            );

            foreach ($seqs as $idx => $seqData) {
                $seq = Sequence::firstOrCreate(
                    ['ttre_seq' => $seqData['ttre'], 'id_crs' => $cours->id_crs],
                    [
                        'ttre_seq' => $seqData['ttre'],
                        'desc_seq' => $seqData['desc'],
                        'id_crs'   => $cours->id_crs,
                        'ordre'    => $idx + 1,
                    ]
                );

                for ($r = 0; $r < 2; $r++) {
                    $niv = ($r == 0) ? 1 : 2;
                    $typKey = $typRessKeys[$idx % count($typRessKeys)];
                    Ressource::firstOrCreate(
                        ['id_seq' => $seq->id_seq, 'niv_comp' => $niv, 'id_typ_ress' => $typRess[$typKey]->id_typ_ress],
                        [
                            'niv_comp'       => $niv,
                            'dte_creat_ress' => now()->subMonths(rand(1,6))->format('Y-m-d'),
                            'dte_maj_ress'   => null,
                            'id_seq'         => $seq->id_seq,
                            'id_typ_ress'    => $typRess[$typKey]->id_typ_ress,
                        ]
                    );
                }
            }
        }

        // 11. ACTIVITES DE TEST (2 cas seulement)
        $this->command->info('');
        $this->command->info('Creation des activites de test...');
        
        $typeCreation = TypeActivite::find(1);
        $typeMaj = TypeActivite::find(2);
        $ressources = Ressource::all();
        $ensInfo = $enseignants[0];
        $ensMarketing = $enseignants[1];
        $ensComm = $enseignants[2];
        $ensData = $enseignants[3];
        $ensEntre = $enseignants[4];

        // CAS 1: Activites normales (3 par enseignant)
        $this->command->info('Cas 1: Activites normales (3 par enseignant)');
        foreach ($enseignants as $ens) {
            for ($i = 1; $i <= 3; $i++) {
                $ressource = $ressources->random();
                Activite::create([
                    'date_act' => now()->subDays(rand(1, 60)),
                    'v_hor' => 10 + ($i * 2),
                    'observation' => 'Activite normale ' . $i . ' - ' . $ens->nom_complet,
                    'id_ens' => $ens->id_ens,
                    'id_anee' => $annee->id_anee,
                    'id_typ_act' => ($i % 2 == 0) ? $typeMaj->id_typ_act : $typeCreation->id_typ_act,
                    'id_ress' => $ressource->id_ress,
                    'est_valide' => true,
                    'date_validation' => now(),
                    'valide_par' => 1,
                ]);
            }
        }

        // CAS 2: Quelques activites en attente (2 par enseignant)
        $this->command->info('Cas 2: Activites en attente de validation (2 par enseignant)');
        foreach ($enseignants as $ens) {
            for ($i = 1; $i <= 2; $i++) {
                $ressource = $ressources->random();
                Activite::create([
                    'date_act' => now()->subDays(rand(1, 30)),
                    'v_hor' => 8,
                    'observation' => 'Activite en attente ' . $i . ' - ' . $ens->nom_complet,
                    'id_ens' => $ens->id_ens,
                    'id_anee' => $annee->id_anee,
                    'id_typ_act' => $typeCreation->id_typ_act,
                    'id_ress' => $ressource->id_ress,
                    'est_valide' => false,
                    'date_validation' => null,
                    'valide_par' => null,
                ]);
            }
        }

        // Ajout d'une activite avec depassement pour l'enseignant Informatique
        $this->command->info('Cas special: Depassement pour enseignant Informatique');
        $ressource = $ressources->first();
        Activite::create([
            'date_act' => now()->subDays(10),
            'v_hor' => 150,
            'observation' => 'GROSSE ACTIVITE - Depassement de seuil',
            'id_ens' => $ensInfo->id_ens,
            'id_anee' => $annee->id_anee,
            'id_typ_act' => $typeCreation->id_typ_act,
            'id_ress' => $ressource->id_ress,
            'est_valide' => true,
            'date_validation' => now(),
            'valide_par' => 1,
        ]);

        // RESUME FINAL
        $totalActivites = Activite::count();
        $totalValidees = Activite::where('est_valide', true)->count();
        $totalHeures = Activite::where('est_valide', true)->sum('v_hor');
        
        $this->command->info('');
        $this->command->info('Donnees UVCI inserees avec succes !');
        $this->command->info('');
        $this->command->info('   Departements   : ' . Departement::count());
        $this->command->info('   Enseignants    : ' . Enseignant::count());
        $this->command->info('   Cours          : ' . Cours::count());
        $this->command->info('   Sequences      : ' . Sequence::count());
        $this->command->info('   Ressources     : ' . Ressource::count());
        $this->command->info('   Activites      : ' . $totalActivites . ' (validees: ' . $totalValidees . ', ' . $totalHeures . 'h)');
        $this->command->info('');
        $this->command->info('========== COMPTES DE TEST ==========');
        $this->command->info('Admin       : admin@uvci.edu.ci         | Admin@2026');
        $this->command->info('Secretaire  : secretaire@uvci.edu.ci    | Secret@2026');
        $this->command->info('Enseignant  : konan.kouassi@uvci.edu.ci | Enseignant@2026 (DEPASSEMENT)');
        $this->command->info('Enseignant  : aya.coulibaly@uvci.edu.ci | Enseignant@2026');
        $this->command->info('Enseignant  : joel.koffi@uvci.edu.ci    | Enseignant@2026');
        $this->command->info('=====================================');
    }
}