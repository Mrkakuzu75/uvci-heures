@extends('layouts.admin')
@section('title', $activite ? 'Modifier une activité' : 'Nouvelle activité')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('secretaire.activites') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">{{ $activite ? 'Modifier' : 'Nouvelle' }} activité pédagogique</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ $activite ? route('secretaire.activites.update', $activite) : route('secretaire.activites.store') }}">
                @csrf
                @if($activite) @method('PUT') @endif

                <div class="space-y-4">
                    <!-- Date -->
                    <div>
                        <label class="form-label text-sm font-medium text-gray-700 mb-1">Date de l'activité <span class="text-red-500">*</span></label>
                        <input type="date" name="date_act" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                               value="{{ old('date_act', $activite?->date_act?->format('Y-m-d')) }}" required>
                        @error('date_act') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Enseignant -->
                    <div>
                        <label class="form-label text-sm font-medium text-gray-700 mb-1">Enseignant <span class="text-red-500">*</span></label>
                        <select name="id_ens" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" required>
                            <option value="">Sélectionner un enseignant</option>
                            @foreach($enseignants as $ens)
                                <option value="{{ $ens->id_ens }}" {{ old('id_ens', $activite?->id_ens) == $ens->id_ens ? 'selected' : '' }}>
                                    {{ $ens->nom_complet }} - {{ $ens->grade?->lib_grd ?? 'Sans grade' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_ens') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Année académique -->
                    <div>
                        <label class="form-label text-sm font-medium text-gray-700 mb-1">Année académique <span class="text-red-500">*</span></label>
                        <select name="id_anee" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" required>
                            <option value="">Sélectionner une année</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id_anee }}" {{ old('id_anee', $activite?->id_anee) == $annee->id_anee ? 'selected' : '' }}>
                                    {{ $annee->lib_anee }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_anee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Type d'activité -->
                    <div>
                        <label class="form-label text-sm font-medium text-gray-700 mb-1">Type d'activité <span class="text-red-500">*</span></label>
                        <select name="id_typ_act" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" required>
                            <option value="">Sélectionner un type</option>
                            @foreach($typesActivites as $type)
                                <option value="{{ $type->id_typ_act }}" {{ old('id_typ_act', $activite?->id_typ_act) == $type->id_typ_act ? 'selected' : '' }}>
                                    {{ $type->lib_typ_act }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_typ_act') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Ressource -->
                    <div>
                        <label class="form-label text-sm font-medium text-gray-700 mb-1">Ressource pédagogique</label>
                        <select name="id_ress" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]">
                            <option value="">Sélectionner une ressource (optionnel)</option>
                            @foreach($ressources as $ress)
                                <option value="{{ $ress->id_ress }}" {{ old('id_ress', $activite?->id_ress) == $ress->id_ress ? 'selected' : '' }}>
                                    {{ $ress->sequence?->cours?->intit ?? 'Sans cours' }} - {{ $ress->sequence?->ttre_seq ?? 'Sans séquence' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_ress') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Observation -->
                    <div>
                        <label class="form-label text-sm font-medium text-gray-700 mb-1">Observation</label>
                        <textarea name="observation" rows="3" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]" 
                                  placeholder="Informations complémentaires...">{{ old('observation', $activite?->observation) }}</textarea>
                        @error('observation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Info calcul auto -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                        </svg>
                        <span class="text-xs text-blue-700">Le volume horaire est calculé automatiquement selon la formule : Coefficient × Nombre de séquences du cours</span>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] text-white hover:brightness-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $activite ? 'Mettre à jour' : 'Enregistrer l\'activité' }}
                    </button>
                    <a href="{{ route('secretaire.activites') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-white text-[#5B2E8E] border border-gray-200 hover:bg-gray-50 transition">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection