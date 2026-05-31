@extends('layouts.admin')
@section('title', 'Gestion des séquences')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec retour -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="mb-2">
                <a href="{{ route('secretaire.cours') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour aux cours
                </a>
            </div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ $cours->intit }}</h1>
            <p class="text-gray-500 mt-1">Gestion des séquences pédagogiques</p>
        </div>
        <button onclick="showCreateModal()" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle séquence
        </button>
    </div>

    <!-- Liste des séquences -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Séquences du cours</h3>
            <span class="text-xs text-gray-400">{{ $sequences->count() }} séquence(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Ordre</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Ressources</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sequences as $seq)
                    <tr class="hover:bg-gray-50">
                        <td>
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#5B2E8E]/10 text-[#5B2E8E] font-semibold text-sm">
                                {{ $seq->ordre }}
                            </span>
                        </div>
                        <td class="font-medium text-gray-800">{{ $seq->ttre_seq }}</div>
                        <td class="text-gray-500 text-sm max-w-md">{{ Str::limit($seq->desc_seq ?? '—', 80) }}</div>
                        <td>
                            <a href="{{ route('secretaire.ressources', $seq) }}" class="inline-flex items-center gap-1 text-sm text-[#2E7D32] hover:underline">
                                {{ $seq->ressources->count() }} ressource(s)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        <td class="text-right">
                            <div class="flex gap-2 justify-end">
                                <form method="POST" action="{{ route('secretaire.sequences.destroy', $seq) }}" onsubmit="return confirm('Supprimer cette séquence ?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </tr>
                    @empty
                    <td><td colspan="5" class="text-center py-8 text-gray-400">Aucune séquence pour ce cours</div></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div id="createModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-heading font-bold text-gray-800">Nouvelle séquence</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('secretaire.sequences.store', $cours) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="form-label text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                    <input type="text" name="ttre_seq" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required placeholder="Ex: Introduction à l'algorithmique" autocomplete="off">
                </div>
                <div>
                    <label class="form-label text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="desc_seq" rows="3" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" placeholder="Description détaillée de la séquence..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] text-white hover:brightness-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Créer la séquence
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