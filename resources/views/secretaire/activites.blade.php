@extends('layouts.admin')
@section('title', 'Gestion des activités')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Activités pédagogiques</h1>
            <p class="text-gray-500 mt-1">Suivi et validation des activités des enseignants</p>
        </div>
        <a href="{{ route('secretaire.activites.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle activité
        </a>
    </div>

    <!-- Tableau -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Liste des activités</h3>
            <span class="text-xs text-gray-400">{{ $activites->total() }} activité(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Enseignant</th>
                        <th>Type</th>
                        <th>Cours</th>
                        <th>Séquence</th>
                        <th class="text-right">Volume (h)</th>
                        <th>Statut</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activites as $act)
                    <tr class="hover:bg-gray-50">
                        <td class="text-gray-600 whitespace-nowrap">{{ $act->date_act->format('d/m/Y') }}</td>
                        <td class="font-medium text-gray-800">{{ $act->enseignant?->nom_complet ?? '—' }}</td>
                        <td>
                            @if($act->id_typ_act == 1)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#5B2E8E]/10 text-[#5B2E8E]">Création</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Mise à jour</span>
                            @endif
                        </td>
                        <td class="text-gray-700 max-w-[200px] truncate">{{ $act->ressource?->sequence?->cours?->intit ?? '—' }}</td>
                        <td class="text-gray-500 max-w-[180px] truncate">{{ $act->ressource?->sequence?->ttre_seq ?? '—' }}</td>
                        <td class="text-right font-semibold text-gray-800">{{ number_format($act->v_hor, 2) }} h</div></td>
                        <td>
                            @if($act->est_valide)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Validé
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                                    </svg>
                                    En attente
                                </span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if(!$act->est_valide)
                                <form method="POST" action="{{ route('secretaire.activites.valider', $act) }}" onsubmit="return confirm('Valider cette activité ?')" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-green-50 text-green-700 hover:bg-green-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Valider
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Validé le {{ $act->date_validation?->format('d/m/Y') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-400">Aucune activité</div></td>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SIMPLIFIEE -->
        @if($activites->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex justify-center gap-3">
            {{-- Bouton Précédent --}}
            @if($activites->onFirstPage())
                <span class="inline-flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Précédent
                </span>
            @else
                <a href="{{ $activites->previousPageUrl() }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Précédent
                </a>
            @endif

            {{-- Bouton Suivant --}}
            @if($activites->hasMorePages())
                <a href="{{ $activites->nextPageUrl() }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Suivant
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed">
                    Suivant
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection