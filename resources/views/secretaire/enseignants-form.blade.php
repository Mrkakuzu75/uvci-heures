@extends('layouts.admin')
@section('title', $enseignant ? 'Modifier un enseignant' : 'Nouvel enseignant')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('secretaire.enseignants') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">{{ $enseignant ? 'Modifier' : 'Créer' }} un enseignant</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ $enseignant ? route('secretaire.enseignants.update', $enseignant) : route('secretaire.enseignants.store') }}">
                @csrf
                @if($enseignant) @method('PUT') @endif

                <div class="space-y-4">
                    <!-- Nom et Prénom sur 2 colonnes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                   value="{{ old('nom', $enseignant?->nom) }}" placeholder="KOUADIO" required autocomplete="off">
                            @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="pnom" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                   value="{{ old('pnom', $enseignant?->pnom) }}" placeholder="Jean" required autocomplete="off">
                            @error('pnom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Téléphone et Taux horaire -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" name="tel" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                   value="{{ old('tel', $enseignant?->tel) }}" placeholder="07 58 12 34 56" autocomplete="off">
                            @error('tel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 mb-1">Taux horaire (FCFA/h) <span class="text-red-500">*</span></label>
                            <input type="number" step="100" name="tx_horaire" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                   value="{{ old('tx_horaire', $enseignant?->tx_horaire) }}" placeholder="5000" required autocomplete="off">
                            @error('tx_horaire') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Grade, Statut, Département -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 mb-1">Grade <span class="text-red-500">*</span></label>
                            <select name="id_grd" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" required>
                                <option value="">Sélectionner</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id_grd }}" {{ old('id_grd', $enseignant?->id_grd) == $grade->id_grd ? 'selected' : '' }}>
                                        {{ $grade->lib_grd }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_grd') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-500">*</span></label>
                            <select name="id_stat" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" required>
                                <option value="">Sélectionner</option>
                                @foreach($statuts as $statut)
                                    <option value="{{ $statut->id_stat }}" {{ old('id_stat', $enseignant?->id_stat) == $statut->id_stat ? 'selected' : '' }}>
                                        {{ $statut->lib_stat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_stat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 mb-1">Département <span class="text-red-500">*</span></label>
                            <select name="id_dep" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" required>
                                <option value="">Sélectionner</option>
                                @foreach($departements as $dep)
                                    <option value="{{ $dep->id_dep }}" {{ old('id_dep', $enseignant?->id_dep) == $dep->id_dep ? 'selected' : '' }}>
                                        {{ $dep->lib_dep }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_dep') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @if(!$enseignant)
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <h4 class="font-medium text-gray-800 mb-3 text-sm"> Créer un compte enseignant</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email_compte" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                       value="" placeholder="jean.kouadio@uvci.edu.ci" autocomplete="off">
                                @error('email_compte') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                <input type="password" name="password_compte" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                       value="" placeholder="8 caractères minimum" autocomplete="new-password">
                                @error('password_compte') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label text-sm font-medium text-gray-700 mb-1">Confirmer</label>
                                <input type="password" name="password_compte_confirmation" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                       value="" placeholder="Retapez le mot de passe" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Boutons -->
                    <div class="flex gap-3 mt-6 pt-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] text-white hover:brightness-105 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $enseignant ? 'Mettre à jour' : 'Créer l\'enseignant' }}
                        </button>
                        <a href="{{ route('secretaire.enseignants') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-white text-[#5B2E8E] border border-gray-200 hover:bg-gray-50 transition">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection