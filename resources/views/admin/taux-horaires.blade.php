@extends('layouts.admin')
@section('title', 'Taux horaires')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Taux horaires</h1>
            <p class="text-gray-500 mt-1">Gestion des taux horaires par enseignant</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Liste des enseignants</h3>
            <span class="text-xs text-gray-400">{{ $enseignants->count() }} enseignant(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Enseignant</th>
                        <th>Grade</th>
                        <th>Département</th>
                        <th>Statut</th>
                        <th>Taux horaire (FCFA/h)</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enseignants as $ens)
                    <tr class="hover:bg-gray-50 transition">
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar avatar-purple">{{ $ens->initiales }}</div>
                                <span class="font-medium text-gray-800">{{ $ens->nom_complet }}</span>
                            </div>
                        </td>
                        <td class="text-gray-600">{{ $ens->grade?->lib_grd ?? '—' }}</td>
                        <td class="text-gray-600">{{ $ens->departement?->lib_dep ?? '—' }}</td>
                        <td>
                            @if(strtolower($ens->statut?->lib_stat ?? '') === 'permanent')
                                <span class="badge badge-success">Permanent</span>
                            @else
                                <span class="badge badge-warning">Vacataire</span>
                            @endif
                        </td>
                        <td class="font-semibold text-gray-800">{{ number_format($ens->tx_horaire, 0) }} FCFA</td>
                        <td class="text-right">
                            <button onclick="editTaux({{ $ens->id_ens }}, '{{ $ens->nom_complet }}', {{ $ens->tx_horaire }})" 
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400">Aucun enseignant<td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal modification taux -->
<div id="tauxModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-heading font-bold text-gray-800">Modifier le taux horaire</h3>
            <button onclick="closeTauxModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="tauxForm" method="POST">
            @csrf
            @method('PUT')
            <p class="text-gray-600 mb-4" id="enseignantName"></p>
            <div>
                <label class="form-label">Taux horaire (FCFA/h)</label>
                <input type="number" name="tx_horaire" id="tx_horaire" class="form-input" step="100" min="0" required>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary flex-1">Enregistrer</button>
                <button type="button" onclick="closeTauxModal()" class="btn btn-secondary flex-1">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editTaux(id, name, currentRate) {
        document.getElementById('enseignantName').innerHTML = '<strong class="text-gray-800">' + name + '</strong>';
        document.getElementById('tx_horaire').value = currentRate;
        document.getElementById('tauxForm').action = '/admin/taux-horaires/' + id;
        document.getElementById('tauxModal').classList.remove('hidden');
        document.getElementById('tauxModal').classList.add('flex');
    }
    
    function closeTauxModal() {
        document.getElementById('tauxModal').classList.add('hidden');
        document.getElementById('tauxModal').classList.remove('flex');
    }
</script>
@endsection