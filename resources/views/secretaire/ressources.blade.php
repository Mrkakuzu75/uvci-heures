@extends('layouts.admin')
@section('title', 'Gestion des ressources')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Ressources pédagogiques</h1>
            <p class="text-gray-500 mt-1">Gestion des ressources par séquence</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('secretaire.sequences', $sequence->cours) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux séquences
            </a>
            <button onclick="showCreateModal()" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle ressource
            </button>
        </div>
    </div>

    <!-- Info séquence -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Séquence</p>
                <p class="font-medium text-gray-800">{{ $sequence->ttre_seq }}</p>
                <p class="text-xs text-gray-500">Cours : {{ $sequence->cours->intit }}</p>
            </div>
        </div>
    </div>

    <!-- Liste des ressources -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Ressources de la séquence</h3>
            <span class="text-xs text-gray-400">{{ $ressources->count() }} ressource(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Niveau de complexité</th>
                        <th>Date de création</th>
                        <th>Date de mise à jour</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ressources as $r)
                    <tr>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#5B2E8E]/10 text-[#5B2E8E]">
                                {{ $r->typeRessource?->lib_typ_ress ?? '—' }}
                            </span>
                        </div>
                        <td>
                            <span class="inline-flex items-center gap-1">
                                <span class="w-6 h-6 rounded-lg inline-flex items-center justify-center font-bold text-xs
                                    {{ $r->niv_comp == 1 ? 'bg-blue-100 text-blue-700' : ($r->niv_comp == 2 ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700') }}">
                                    N{{ $r->niv_comp }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    @if($r->niv_comp == 1) Contenus simples + quiz
                                    @elseif($r->niv_comp == 2) Niv.1 + interactifs
                                    @else Serious games, simulations
                                    @endif
                                </span>
                            </span>
                        </div>
                        <td class="text-gray-600">{{ $r->dte_creat_ress ? $r->dte_creat_ress->format('d/m/Y') : '—' }}</div></td>
                        <td class="text-gray-600">{{ $r->dte_maj_ress ? $r->dte_maj_ress->format('d/m/Y') : '—' }}</div></tr>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400">Aucune ressource pour cette séquence</div></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div id="createModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-heading font-bold text-gray-800">Nouvelle ressource</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('secretaire.ressources.store', $sequence) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="form-label text-sm font-medium text-gray-700 mb-1">Type de ressource <span class="text-red-500">*</span></label>
                    <select name="id_typ_ress" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required>
                        <option value="">Sélectionner</option>
                        @foreach($typesRessources as $type)
                            <option value="{{ $type->id_typ_ress }}">{{ $type->lib_typ_ress }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label text-sm font-medium text-gray-700 mb-1">Niveau de complexité <span class="text-red-500">*</span></label>
                    <select name="niv_comp" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required>
                        <option value="1">Niveau 1 - Contenus simples + quiz</option>
                        <option value="2">Niveau 2 - Niv.1 + 25% d'activités interactives</option>
                        <option value="3">Niveau 3 - Serious games, simulations</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-sm font-medium text-gray-700 mb-1">Date de création <span class="text-red-500">*</span></label>
                    <input type="date" name="dte_creat_ress" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] text-white hover:brightness-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Créer
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-white text-[#5B2E8E] border border-gray-200 hover:bg-gray-50 transition">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
        document.getElementById('createModal').classList.add('flex');
    }
    
    function closeModal() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createModal').classList.remove('flex');
    }
</script>
@endsection