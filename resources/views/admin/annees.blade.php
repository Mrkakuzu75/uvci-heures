
@extends('layouts.admin')
@section('title', 'Années académiques')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Années académiques</h1>
            <p class="text-gray-500 mt-1">Gestion des années académiques</p>
        </div>
        <button onclick="showCreateModal()" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle année
        </button>
    </div>

    <!-- Liste des années -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Liste des années académiques</h3>
            <span class="text-xs text-gray-400">{{ $annees->count() }} année(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($annees as $annee)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="font-medium text-gray-800">{{ $annee->lib_anee }}</td>
                        <td class="text-gray-600">{{ $annee->dte_dbut->format('d/m/Y') }}</td>
                        <td class="text-gray-600">{{ $annee->dte_fn->format('d/m/Y') }}</td>
                        <td>
                            @if($annee->etat_anee === 'en_cours')
                                <span class="badge badge-success">En cours</span>
                            @elseif($annee->etat_anee === 'cloturee')
                                <span class="badge badge-danger">Clôturée</span>
                            @else
                                <span class="badge badge-warning">À venir</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-400">Aucune année académique</td>
                    </tr>
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
            <h3 class="text-xl font-heading font-bold text-gray-800">Nouvelle année académique</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.annees.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="form-label">Libellé</label>
                    <input type="text" name="lib_anee" class="form-input" required placeholder="Ex: 2024-2025">
                </div>
                <div>
                    <label class="form-label">Date début</label>
                    <input type="date" name="dte_dbut" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Date fin</label>
                    <input type="date" name="dte_fn" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Statut</label>
                    <select name="etat_anee" class="form-select">
                        <option value="a_venir">À venir</option>
                        <option value="en_cours">En cours</option>
                        <option value="cloturee">Clôturée</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary flex-1">Créer</button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary flex-1">Annuler</button>
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