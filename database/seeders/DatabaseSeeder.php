<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{
    Utilisateur, Grade, Statut, Departement,
    Specialite, Semestre, TypeRessource, TypeActivite,
    AnneeAcademique, Enseignant, Cours, Sequence, Ressource
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════════════
        // 1. GRADES
        // ══════════════════════════════════════════════════════════
        foreach ([
            'Assistant', 'Maître-Assistant',
            'Maître de Conférences', 'Professeur Titulaire', 'Chargé de Cours',
        ] as $g) Grade::firstOrCreate(['lib_grd' => $g]);

        // ══════════════════════════════════════════════════════════
        // 2. STATUTS
        // ══════════════════════════════════════════════════════════
        Statut::firstOrCreate(['lib_stat' => 'Permanent']);
        Statut::firstOrCreate(['lib_stat' => 'Vacataire']);

        // ══════════════════════════════════════════════════════════
        // 3. DÉPARTEMENTS UVCI
        // ══════════════════════════════════════════════════════════
        foreach ([
            'Département Informatique et Sciences du Numérique',
            'Département Communication et Multimédia',
            'Département Marketing Digital',
            'Département Entrepreneuriat Numérique',
            'Département Sciences des Données',
        ] as $d) Departement::firstOrCreate(['lib_dep' => $d]);

        // ══════════════════════════════════════════════════════════
        // 4. SPÉCIALITÉS UVCI
        // ══════════════════════════════════════════════════════════
        $specsData = [
            'DAS' => 'DAS — Développeur d\'Applications et e-Services',
            'RSI' => 'RSI — Réseaux et Sécurité Informatique',
            'BD'  => 'BD — Base de Données',
            'CM'  => 'CM — Communication et Multimédia',
            'MD'  => 'MD — Marketing Digital',
            'MED' => 'MED — Médiation Numérique',
            'SA'  => 'SA — Systèmes d\'Administration',
            'GEO' => 'GEO — Géomatique et SIG',
            'MAI' => 'Master — Intelligence Artificielle et Science des Données',
            'MCY' => 'Master — Cybersécurité et Réseaux',
            'MDL' => 'Master — Développement Logiciel et Mobile',
            'MMP' => 'Master — Management de Projets Numériques',
            'MBC' => 'Master — Blockchain et Cryptographie',
            'AI'  => 'AI — Intelligence Artificielle',
            'RO'  => 'RO — Robotique',
            'ENT' => 'ENT — Entrepreneuriat Digital',
        ];
        $specs = [];
        foreach ($specsData as $code => $lib) {
            $specs[$code] = Specialite::firstOrCreate(['lib_spec' => $lib]);
        }

        // ══════════════════════════════════════════════════════════
        // 5. SEMESTRES
        // ══════════════════════════════════════════════════════════
        $semestresData = [
            'S1L1' => 'Semestre 1 — L1',
            'S2L1' => 'Semestre 2 — L1',
            'S3L2' => 'Semestre 3 — L2',
            'S4L2' => 'Semestre 4 — L2',
            'S5L3' => 'Semestre 5 — L3',
            'S6L3' => 'Semestre 6 — L3',
            'S1M1' => 'Semestre 1 — M1',
            'S2M1' => 'Semestre 2 — M1',
            'S3M2' => 'Semestre 3 — M2',
            'S4M2' => 'Semestre 4 — M2',
        ];
        $sems = [];
        foreach ($semestresData as $code => $lib) {
            $sems[$code] = Semestre::firstOrCreate(['lib_sem' => $lib]);
        }

        // ══════════════════════════════════════════════════════════
        // 6. TYPES DE RESSOURCES PÉDAGOGIQUES
        // ══════════════════════════════════════════════════════════
        $typRessData = [
            'TXT'  => 'Contenu textuel',
            'VID'  => 'Vidéo pédagogique',
            'PDF'  => 'Document PDF',
            'QUIZ' => 'Quiz',
            'ACT'  => 'Activité interactive',
            'EVAL' => 'Évaluation',
            'SG'   => 'Serious Game',
            'SIM'  => 'Simulation',
            'WEB'  => 'Webinaire',
        ];
        $typRess = [];
        foreach ($typRessData as $code => $lib) {
            $typRess[$code] = TypeRessource::firstOrCreate(['lib_typ_ress' => $lib]);
        }

        // ══════════════════════════════════════════════════════════
        // 7. TYPES D'ACTIVITÉS
        // ══════════════════════════════════════════════════════════
        TypeActivite::firstOrCreate(['id_typ_act' => 1], ['lib_typ_act' => 'Création de ressource']);
        TypeActivite::firstOrCreate(['id_typ_act' => 2], ['lib_typ_act' => 'Mise à jour de ressource']);

        // ══════════════════════════════════════════════════════════
        // 8. ANNÉES ACADÉMIQUES
        // ══════════════════════════════════════════════════════════
        AnneeAcademique::firstOrCreate(['lib_anee' => '2024-2025'], [
            'dte_dbut' => '2024-10-01', 'dte_fn' => '2025-07-31', 'etat_anee' => 'cloturee',
        ]);
        AnneeAcademique::firstOrCreate(['lib_anee' => '2025-2026'], [
            'dte_dbut' => '2025-10-01', 'dte_fn' => '2026-07-31', 'etat_anee' => 'en_cours',
        ]);

        // ══════════════════════════════════════════════════════════
        // 9. UTILISATEURS
        // ══════════════════════════════════════════════════════════
        Utilisateur::firstOrCreate(['email' => 'admin@uvci.edu.ci'], [
            'login' => 'admin', 'mdp' => Hash::make('Admin@2026'), 'role' => 'administrateur',
        ]);
        Utilisateur::firstOrCreate(['email' => 'secretaire@uvci.edu.ci'], [
            'login' => 'secretaire', 'mdp' => Hash::make('Secret@2026'), 'role' => 'secretaire',
        ]);

        $ensUsers = [
            ['email'=>'konan.kouassi@uvci.edu.ci',   'login'=>'konan.kouassi',   'nom'=>'KOUASSI',   'pnom'=>'Konan Éric'],
            ['email'=>'aya.coulibaly@uvci.edu.ci',    'login'=>'aya.coulibaly',   'nom'=>'COULIBALY', 'pnom'=>'Aya Marie'],
            ['email'=>'joel.koffi@uvci.edu.ci',       'login'=>'joel.koffi',      'nom'=>'KOFFI',     'pnom'=>'Joël Arnaud'],
            ['email'=>'brou.yao@uvci.edu.ci',         'login'=>'brou.yao',        'nom'=>'YAO',       'pnom'=>'Brou Théodore'],
            ['email'=>'mariam.traore@uvci.edu.ci',    'login'=>'mariam.traore',   'nom'=>'TRAORÉ',    'pnom'=>'Mariam'],
        ];
        $enseignantUsers = [];
        foreach ($ensUsers as $eu) {
            $enseignantUsers[] = Utilisateur::firstOrCreate(
                ['email' => $eu['email']],
                ['login' => $eu['login'], 'mdp' => Hash::make('Enseignant@2026'), 'role' => 'enseignant']
            );
        }

        // ══════════════════════════════════════════════════════════
        // 10. ENSEIGNANTS
        // ══════════════════════════════════════════════════════════
        $gMC = Grade::where('lib_grd','Maître de Conférences')->first();
        $gMA = Grade::where('lib_grd','Maître-Assistant')->first();
        $gAS = Grade::where('lib_grd','Assistant')->first();
        $gCC = Grade::where('lib_grd','Chargé de Cours')->first();
        $sP  = Statut::where('lib_stat','Permanent')->first();
        $sV  = Statut::where('lib_stat','Vacataire')->first();
        $dep = Departement::where('lib_dep','like','%Informatique%')->first();

        $enseignantsData = [
            ['nom'=>'KOUASSI',  'pnom'=>'Konan Éric',    'tel'=>'0701020304','tx_horaire'=>7500,'id_grd'=>$gMC->id_grd,'id_stat'=>$sP->id_stat,'id_dep'=>$dep->id_dep,'id_util'=>$enseignantUsers[0]->id_util],
            ['nom'=>'COULIBALY','pnom'=>'Aya Marie',      'tel'=>'0505060708','tx_horaire'=>5000,'id_grd'=>$gMA->id_grd,'id_stat'=>$sP->id_stat,'id_dep'=>$dep->id_dep,'id_util'=>$enseignantUsers[1]->id_util],
            ['nom'=>'KOFFI',    'pnom'=>'Joël Arnaud',   'tel'=>'0709101112','tx_horaire'=>4000,'id_grd'=>$gAS->id_grd,'id_stat'=>$sV->id_stat,'id_dep'=>$dep->id_dep,'id_util'=>$enseignantUsers[2]->id_util],
            ['nom'=>'YAO',      'pnom'=>'Brou Théodore', 'tel'=>'0712131415','tx_horaire'=>4500,'id_grd'=>$gCC->id_grd,'id_stat'=>$sV->id_stat,'id_dep'=>$dep->id_dep,'id_util'=>$enseignantUsers[3]->id_util],
            ['nom'=>'TRAORÉ',   'pnom'=>'Mariam',        'tel'=>'0716171819','tx_horaire'=>6000,'id_grd'=>$gMA->id_grd,'id_stat'=>$sP->id_stat,'id_dep'=>$dep->id_dep,'id_util'=>$enseignantUsers[4]->id_util],
        ];
        $enseignants = [];
        foreach ($enseignantsData as $ed) {
            $enseignants[] = Enseignant::firstOrCreate(['id_util' => $ed['id_util']], $ed);
        }

        // ══════════════════════════════════════════════════════════
        // 11. COURS + SÉQUENCES + RESSOURCES
        //     Chaîne complète : Cours → Séquences → Ressources
        // ══════════════════════════════════════════════════════════

        $coursData = [
            // DAS — Semestre 1 L1
            [
                'intit'=>'Introduction à la Programmation','filre'=>'DAS','niv'=>'L1',
                'nbh_bse'=>10,'nbr_crdt'=>2,'nbr_squce'=>40,
                'id_sem'=>$sems['S1L1']->id_sem,'id_spec'=>$specs['DAS']->id_spec,
                'sequences' => [
                    ['ttre'=>'Bases du langage Python','desc'=>'Variables, types, opérateurs'],
                    ['ttre'=>'Structures de contrôle','desc'=>'If, while, for'],
                    ['ttre'=>'Fonctions et modules','desc'=>'Définition et appel de fonctions'],
                    ['ttre'=>'Manipulation de fichiers','desc'=>'Lecture et écriture de fichiers'],
                ],
            ],
            // DAS — Semestre 1 L1
            [
                'intit'=>'Algorithmique et Structures de Données','filre'=>'DAS','niv'=>'L1',
                'nbh_bse'=>10,'nbr_crdt'=>3,'nbr_squce'=>40,
                'id_sem'=>$sems['S1L1']->id_sem,'id_spec'=>$specs['DAS']->id_spec,
                'sequences' => [
                    ['ttre'=>'Notions d\'algorithme','desc'=>'Définition et propriétés'],
                    ['ttre'=>'Tableaux et listes','desc'=>'Structures de données linéaires'],
                    ['ttre'=>'Tris et recherches','desc'=>'Algorithmes classiques de tri'],
                    ['ttre'=>'Arbres et graphes','desc'=>'Structures arborescentes'],
                ],
            ],
            // DAS — Semestre 3 L2
            [
                'intit'=>'Développement Web Front-End','filre'=>'DAS','niv'=>'L2',
                'nbh_bse'=>20,'nbr_crdt'=>3,'nbr_squce'=>80,
                'id_sem'=>$sems['S3L2']->id_sem,'id_spec'=>$specs['DAS']->id_spec,
                'sequences' => [
                    ['ttre'=>'HTML5 et sémantique','desc'=>'Structure des pages web'],
                    ['ttre'=>'CSS3 et Flexbox/Grid','desc'=>'Mise en forme et layouts'],
                    ['ttre'=>'JavaScript ES6+','desc'=>'Programmation côté client'],
                    ['ttre'=>'Framework React.js','desc'=>'Composants et état'],
                    ['ttre'=>'Accessibilité et SEO','desc'=>'Bonnes pratiques web'],
                ],
            ],
            // RSI — Semestre 3 L2
            [
                'intit'=>'Sécurité des Réseaux Informatiques','filre'=>'RSI','niv'=>'L2',
                'nbh_bse'=>20,'nbr_crdt'=>3,'nbr_squce'=>80,
                'id_sem'=>$sems['S3L2']->id_sem,'id_spec'=>$specs['RSI']->id_spec,
                'sequences' => [
                    ['ttre'=>'Cryptographie et chiffrement','desc'=>'Algorithmes symétriques et asymétriques'],
                    ['ttre'=>'Protocoles sécurisés','desc'=>'TLS, HTTPS, SSH'],
                    ['ttre'=>'Firewalls et VPN','desc'=>'Sécurisation du réseau'],
                    ['ttre'=>'Détection d\'intrusion','desc'=>'IDS et IPS'],
                ],
            ],
            // RSI — Semestre 5 L3
            [
                'intit'=>'Administration Systèmes Linux','filre'=>'RSI','niv'=>'L3',
                'nbh_bse'=>20,'nbr_crdt'=>4,'nbr_squce'=>80,
                'id_sem'=>$sems['S5L3']->id_sem,'id_spec'=>$specs['RSI']->id_spec,
                'sequences' => [
                    ['ttre'=>'Installation et configuration Linux','desc'=>'Distributions et partitionnement'],
                    ['ttre'=>'Gestion des utilisateurs','desc'=>'Droits et permissions'],
                    ['ttre'=>'Services réseau','desc'=>'DNS, DHCP, NFS'],
                    ['ttre'=>'Supervision et monitoring','desc'=>'Nagios, Zabbix'],
                ],
            ],
            // BD — Semestre 3 L2
            [
                'intit'=>'Bases de Données Relationnelles','filre'=>'BD','niv'=>'L2',
                'nbh_bse'=>10,'nbr_crdt'=>3,'nbr_squce'=>40,
                'id_sem'=>$sems['S3L2']->id_sem,'id_spec'=>$specs['BD']->id_spec,
                'sequences' => [
                    ['ttre'=>'Modèle Entité-Association','desc'=>'Conception et MCD'],
                    ['ttre'=>'SQL — Requêtes de base','desc'=>'SELECT, INSERT, UPDATE, DELETE'],
                    ['ttre'=>'SQL — Jointures et sous-requêtes','desc'=>'Requêtes avancées'],
                    ['ttre'=>'Transactions et concurrence','desc'=>'ACID et verrous'],
                ],
            ],
            // BD — Semestre 5 L3
            [
                'intit'=>'Administration des Bases de Données','filre'=>'BD','niv'=>'L3',
                'nbh_bse'=>20,'nbr_crdt'=>4,'nbr_squce'=>80,
                'id_sem'=>$sems['S5L3']->id_sem,'id_spec'=>$specs['BD']->id_spec,
                'sequences' => [
                    ['ttre'=>'Installation MySQL / PostgreSQL','desc'=>'Configuration serveur'],
                    ['ttre'=>'Sauvegarde et restauration','desc'=>'Stratégies de backup'],
                    ['ttre'=>'Optimisation et indexation','desc'=>'EXPLAIN et tuning'],
                    ['ttre'=>'Haute disponibilité','desc'=>'Réplication et clustering'],
                ],
            ],
            // MD — Semestre 1 M1
            [
                'intit'=>'Marketing Digital et e-Commerce','filre'=>'MD','niv'=>'M1',
                'nbh_bse'=>20,'nbr_crdt'=>4,'nbr_squce'=>80,
                'id_sem'=>$sems['S1M1']->id_sem,'id_spec'=>$specs['MD']->id_spec,
                'sequences' => [
                    ['ttre'=>'Stratégie digitale','desc'=>'Positionnement et cibles'],
                    ['ttre'=>'SEO et référencement','desc'=>'Optimisation pour les moteurs'],
                    ['ttre'=>'Réseaux sociaux','desc'=>'Community management'],
                    ['ttre'=>'Google Analytics','desc'=>'Mesure et analytics'],
                ],
            ],
        ];

        // Niveaux de complexité à alterner : 1, 2, 3
        // Types de ressources à alterner
        $typRessKeys = ['TXT','VID','QUIZ','ACT','EVAL','PDF'];

        foreach ($coursData as $cd) {
            $seqs     = $cd['sequences'];
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

                // 2 à 3 ressources par séquence avec des niveaux variés
                $ressourcesParSeq = [
                    // [niv_comp, type_key]
                    [1, $typRessKeys[$idx % count($typRessKeys)]],
                    [2, 'QUIZ'],
                    [3, 'ACT'],
                ];
                // Pour les premières séquences mettre 2 ressources, les suivantes 3
                $nbRess = ($idx < 2) ? 2 : 3;

                for ($r = 0; $r < $nbRess; $r++) {
                    [$niv, $typKey] = $ressourcesParSeq[$r];
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

        // ══════════════════════════════════════════════════════════
        // RÉSUMÉ FINAL
        // ══════════════════════════════════════════════════════════
        $this->command->info('');
        $this->command->info('✅  Données UVCI insérées avec succès !');
        $this->command->info('');
        $this->command->info('   Spécialités    : ' . \App\Models\Specialite::count());
        $this->command->info('   Semestres      : ' . \App\Models\Semestre::count());
        $this->command->info('   Cours          : ' . \App\Models\Cours::count());
        $this->command->info('   Séquences      : ' . \App\Models\Sequence::count());
        $this->command->info('   Ressources     : ' . \App\Models\Ressource::count());
        $this->command->info('   Enseignants    : ' . \App\Models\Enseignant::count());
        $this->command->info('');
        $this->command->info('══════════ COMPTES DE TEST ══════════');
        $this->command->info('Admin       : admin@uvci.edu.ci         | Admin@2026');
        $this->command->info('Secrétaire  : secretaire@uvci.edu.ci    | Secret@2026');
        $this->command->info('Enseignant  : konan.kouassi@uvci.edu.ci | Enseignant@2026');
        $this->command->info('Enseignant  : aya.coulibaly@uvci.edu.ci | Enseignant@2026');
        $this->command->info('Enseignant  : joel.koffi@uvci.edu.ci    | Enseignant@2026');
        $this->command->info('════════════════════════════════════');
    }
}
