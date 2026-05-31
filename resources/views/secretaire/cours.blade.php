@extends('layouts.admin')
@section('title', 'Gestion des cours')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Cours</h1>
            <p class="text-gray-500 mt-1">Gestion des cours et séquences pédagogiques</p>
        </div>
        <button onclick="showCreateModal()" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau cours
        </button>
    </div>

    <!-- Liste des cours -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Liste des cours</h3>
            <span class="text-xs text-gray-400">{{ $cours->total() }} cours(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Intitulé</th>
                        <th>Filière</th>
                        <th>Niveau</th>
                        <th>Semestre</th>
                        <th>Heures base</th>
                        <th>Séquences</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cours as $c)
                    <tr>
                        <td class="font-medium text-gray-800">{{ $c->intit }}</td>
                        <td class="text-gray-600">{{ $c->filre }}</div></td>
                        <td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#5B2E8E]/10 text-[#5B2E8E]">{{ $c->niv }}</span></div></td>
                        <td class="text-gray-600">{{ $c->semestre?->lib_sem ?? '—' }}</div></td>
                        <td class="text-gray-600">{{ $c->nbh_bse }}h</div></td>
                        <td>
                            <a href="{{ route('secretaire.sequences', $c) }}" class="inline-flex items-center gap-1 text-sm text-[#2E7D32] hover:underline">
                                {{ $c->sequences->count() }} séquence(s)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-8 text-gray-400">Aucun cours</div></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cours->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100">
            {{ $cours->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Création (version compacte) -->
<div id="createModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl max-w-lg w-full max-h-[85vh] overflow-hidden shadow-xl">
        
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] px-5 py-3 flex justify-between items-center">
            <h3 class="text-white font-heading font-bold text-base">➕ Nouveau cours</h3>
            <button onclick="closeModal()" class="text-white/70 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Formulaire compact -->
        <form method="POST" action="{{ route('secretaire.cours.store') }}">
            @csrf
            <div class="p-4 overflow-y-auto" style="max-height: calc(85vh - 120px);">
                <div class="space-y-3">
                    <!-- Intitulé (pleine largeur) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Intitulé du cours</label>
                        <input type="text" name="intit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required placeholder="Ex: Algorithmique">
                    </div>
                    
                    <!-- Filière et Niveau -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Filière</label>
                            <input type="text" name="filre" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required placeholder="Informatique">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Niveau</label>
                            <select name="niv" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="L1">L1</option>
                                <option value="L2">L2</option>
                                <option value="L3">L3</option>
                                <option value="M1">M1</option>
                                <option value="M2">M2</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Semestre et Spécialité -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Semestre</label>
                            <select name="id_sem" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                <option value="">Sélectionner</option>
                                @foreach($semestres as $sem)
                                    <option value="{{ $sem->id_sem }}">{{ $sem->lib_sem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Spécialité</label>
                            <select name="id_spec" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                <option value="">Sélectionner</option>
                                @foreach($specialites as $spec)
                                    <option value="{{ $spec->id_spec }}">{{ $spec->lib_spec }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Heures base, Crédits, Séquences -->
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Heures base</label>
                            <input type="number" name="nbh_bse" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required placeholder="45">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Crédits</label>
                            <input type="number" name="nbr_crdt" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required placeholder="6">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Séquences</label>
                            <input type="number" name="nbr_squce" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required placeholder="20">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="border-t border-gray-100 px-4 py-3 flex gap-2 bg-gray-50">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 rounded-lg text-sm font-semibold bg-[#2E7D32] text-white hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 rounded-lg text-sm font-semibold bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
        document.getElementById('createModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createModal').classList.remove('flex');
        document.body.style.overflow = '';
    }
    
    // Fermer en cliquant à l'extérieur
    document.getElementById('createModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Fermer avec Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection