@extends('layouts.admin')
@section('title', 'Paramètres de calcul')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Paramètres de calcul</h1>
            <p class="text-gray-500 mt-1">Configuration des coefficients pour le calcul des heures</p>
        </div>
    </div>

    <!-- Formule de calcul -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-gray-800 mb-1">Formule de calcul du volume horaire</h3>
                <p class="text-sm text-gray-600 mb-2">
                    <span class="font-mono bg-blue-100 px-2 py-0.5 rounded">Volume horaire = Coefficient × Nombre de séquences du cours</span>
                </p>
                <p class="text-xs text-gray-500">
                    Le coefficient dépend du <strong>type d'activité</strong> (création ou mise à jour) et du <strong>niveau de complexité</strong> de la ressource (1, 2 ou 3).
                </p>
                <div class="mt-3 text-xs text-gray-500 space-y-1">
                    <p>• <strong>Niveau 1</strong> : Contenus simples + quiz + évaluations</p>
                    <p>• <strong>Niveau 2</strong> : Niveau 1 avec 25% d'activités interactives</p>
                    <p>• <strong>Niveau 3</strong> : Serious games, simulations, haute qualité</p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.parametres.update') }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Coefficients de création -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-gray-800">Création de ressources</h3>
                    <span class="badge badge-purple">Coefficients</span>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label mb-0">Niveau 1</label>
                            <span class="text-xs text-gray-400">Contenus simples + quiz</span>
                        </div>
                        <input type="number" step="0.01" name="creation_niv1" class="form-input" value="{{ $coefficients[1][1] ?? 0.4 }}" required>
                        <p class="text-xs text-gray-400 mt-1">Exemple: cours avec 20 séquences → {{ ($coefficients[1][1] ?? 0.4) * 20 }} heures</p>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label mb-0">Niveau 2</label>
                            <span class="text-xs text-gray-400">Niv.1 + 25% d'activités interactives</span>
                        </div>
                        <input type="number" step="0.01" name="creation_niv2" class="form-input" value="{{ $coefficients[1][2] ?? 0.75 }}" required>
                        <p class="text-xs text-gray-400 mt-1">Exemple: cours avec 20 séquences → {{ ($coefficients[1][2] ?? 0.75) * 20 }} heures</p>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label mb-0">Niveau 3</label>
                            <span class="text-xs text-gray-400">Serious games, simulations</span>
                        </div>
                        <input type="number" step="0.01" name="creation_niv3" class="form-input" value="{{ $coefficients[1][3] ?? 1.5 }}" required>
                        <p class="text-xs text-gray-400 mt-1">Exemple: cours avec 20 séquences → {{ ($coefficients[1][3] ?? 1.5) * 20 }} heures</p>
                    </div>
                </div>
            </div>

            <!-- Coefficients de mise à jour -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-gray-800">Mise à jour de ressources</h3>
                    <span class="badge badge-info">Coefficients</span>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label mb-0">Niveau 1</label>
                            <span class="text-xs text-gray-400">Contenus simples + quiz</span>
                        </div>
                        <input type="number" step="0.01" name="maj_niv1" class="form-input" value="{{ $coefficients[2][1] ?? 0.2 }}" required>
                        <p class="text-xs text-gray-400 mt-1">Exemple: mise à jour → {{ ($coefficients[2][1] ?? 0.2) * 20 }} heures</p>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label mb-0">Niveau 2</label>
                            <span class="text-xs text-gray-400">Niv.1 + 25% d'activités interactives</span>
                        </div>
                        <input type="number" step="0.01" name="maj_niv2" class="form-input" value="{{ $coefficients[2][2] ?? 0.375 }}" required>
                        <p class="text-xs text-gray-400 mt-1">Exemple: mise à jour → {{ ($coefficients[2][2] ?? 0.375) * 20 }} heures</p>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label mb-0">Niveau 3</label>
                            <span class="text-xs text-gray-400">Serious games, simulations</span>
                        </div>
                        <input type="number" step="0.01" name="maj_niv3" class="form-input" value="{{ $coefficients[2][3] ?? 0.75 }}" required>
                        <p class="text-xs text-gray-400 mt-1">Exemple: mise à jour → {{ ($coefficients[2][3] ?? 0.75) * 20 }} heures</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seuil heures complémentaires -->
        <div class="card mt-6">
            <div class="card-header">
                <h3 class="text-gray-800">Heures complémentaires</h3>
                <span class="badge badge-warning">Majoration 150%</span>
            </div>
            <div class="p-5">
                <div class="max-w-md">
                    <label class="form-label">Seuil statutaire (heures)</label>
                    <input type="number" name="seuil" class="form-input" value="{{ $seuil ?? 192 }}" required>
                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-1">Formule de calcul des heures complémentaires :</p>
                        <p class="text-xs text-gray-500 font-mono">
                            Si Volume total > Seuil alors<br>
                            Heures complémentaires = Volume total - Seuil<br>
                            Montant complémentaire = Heures complémentaires × Taux horaire × 1.5
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton Enregistrer -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Enregistrer les paramètres
            </button>
        </div>
    </form>
</div>
@endsection