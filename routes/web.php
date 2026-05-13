<?php

use App\Http\Controllers\ExportEnseignantsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, AdminController, SecretaireController,
    EnseignantController, PaiementController
};

// ── Authentification ──────────────────────────────────────────
Route::get('/',       [AuthController::class, 'showLogin'])->name('login');
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Administrateur ────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:administrateur'])->group(function () {
    Route::get('/dashboard',                       [AdminController::class, 'dashboard'])->name('dashboard');

    // Utilisateurs
    Route::get('/utilisateurs',                    [AdminController::class, 'utilisateurs'])->name('utilisateurs');
    Route::get('/utilisateurs/create',             [AdminController::class, 'createUtilisateur'])->name('utilisateurs.create');
    Route::post('/utilisateurs',                   [AdminController::class, 'storeUtilisateur'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{utilisateur}/edit', [AdminController::class, 'editUtilisateur'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{utilisateur}',      [AdminController::class, 'updateUtilisateur'])->name('utilisateurs.update');
    Route::delete('/utilisateurs/{utilisateur}',   [AdminController::class, 'destroyUtilisateur'])->name('utilisateurs.destroy');

    // Années académiques
    Route::get('/annees',                          [AdminController::class, 'annees'])->name('annees');
    Route::post('/annees',                         [AdminController::class, 'storeAnnee'])->name('annees.store');

    // Paramètres de calcul
    Route::get('/parametres',                      [AdminController::class, 'parametres'])->name('parametres');
    Route::put('/parametres',                      [AdminController::class, 'updateParametres'])->name('parametres.update');

    // Taux horaires
    Route::get('/taux-horaires',                   [AdminController::class, 'tauxHoraires'])->name('taux-horaires');
    Route::put('/taux-horaires/{enseignant}',       [AdminController::class, 'updateTaux'])->name('taux-horaires.update');
});

// ── Secrétaire ────────────────────────────────────────────────
Route::prefix('secretaire')->name('secretaire.')->middleware(['auth', 'role:secretaire'])->group(function () {
    Route::get('/dashboard',                              [SecretaireController::class, 'dashboard'])->name('dashboard');

    // Enseignants
    Route::get('/enseignants',                            [SecretaireController::class, 'enseignants'])->name('enseignants');
    Route::get('/enseignants/create',                     [SecretaireController::class, 'createEnseignant'])->name('enseignants.create');
    Route::post('/enseignants',                           [SecretaireController::class, 'storeEnseignant'])->name('enseignants.store');
    Route::get('/enseignants/export-excel', [ExportEnseignantsController::class, 'excel'])->name('enseignants.export-excel');
    Route::get('/enseignants/{enseignant}/edit',          [SecretaireController::class, 'editEnseignant'])->name('enseignants.edit');
    Route::put('/enseignants/{enseignant}',               [SecretaireController::class, 'updateEnseignant'])->name('enseignants.update');
    Route::delete('/enseignants/{enseignant}',            [SecretaireController::class, 'destroyEnseignant'])->name('enseignants.destroy');

    // Cours
    Route::get('/cours',                                  [SecretaireController::class, 'cours'])->name('cours');
    Route::post('/cours',                                 [SecretaireController::class, 'storeCours'])->name('cours.store');

    // Séquences
    Route::get('/cours/{cours}/sequences',                [SecretaireController::class, 'sequences'])->name('sequences');
    Route::post('/cours/{cours}/sequences',               [SecretaireController::class, 'storeSequence'])->name('sequences.store');
    Route::delete('/sequences/{sequence}',                [SecretaireController::class, 'destroySequence'])->name('sequences.destroy');

    // Ressources
    Route::get('/sequences/{sequence}/ressources',        [SecretaireController::class, 'ressources'])->name('ressources');
    Route::post('/sequences/{sequence}/ressources',       [SecretaireController::class, 'storeRessource'])->name('ressources.store');
    Route::delete('/ressources/{ressource}',              [SecretaireController::class, 'destroyRessource'])->name('ressources.destroy');

    // Activités
    Route::get('/activites',                              [SecretaireController::class, 'activites'])->name('activites');
    Route::get('/activites/create',                       [SecretaireController::class, 'createActivite'])->name('activites.create');
    Route::post('/activites',                             [SecretaireController::class, 'storeActivite'])->name('activites.store');

    // États de paiement
    Route::get('/paiements',                              [PaiementController::class, 'etatPaiements'])->name('paiements');
    Route::get('/paiements/pdf-global',                   [PaiementController::class, 'pdfEtatGlobal'])->name('paiements.pdf-global');
    Route::get('/paiements/fiche/{enseignant}',           [PaiementController::class, 'ficheIndividuelle'])->name('paiements.fiche');
});

// ── Enseignant ────────────────────────────────────────────────
Route::prefix('enseignant')->name('enseignant.')->middleware(['auth', 'role:enseignant'])->group(function () {
    Route::get('/dashboard',                              [EnseignantController::class, 'dashboard'])->name('dashboard');
    Route::get('/activites',                              [EnseignantController::class, 'activites'])->name('activites');
    Route::get('/recapitulatif',                          [PaiementController::class, 'recapitulatifEnseignant'])->name('recapitulatif');
});
