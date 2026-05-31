@extends('layouts.admin')
@section('title', 'Gestion des enseignants')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Enseignants</h1>
            <p class="text-gray-500 mt-1">Gestion des enseignants</p>
        </div>
        <a href="{{ route('secretaire.enseignants.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel enseignant
        </a>
    </div>

    <!-- Filtres -->
    <div class="card">
        <div class="p-4 border-b border-gray-100 flex gap-2.5 flex-wrap">
            <select id="gradeFilter" onchange="filterTable()" class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
                <option value="">Tous les grades</option>
                @foreach($grades ?? [] as $grade)
                    <option value="{{ strtolower($grade->lib_grd) }}">{{ $grade->lib_grd }}</option>
                @endforeach
            </select>
            <select id="statusFilter" onchange="filterTable()" class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
                <option value="">Tous statuts</option>
                <option value="permanent">Permanent</option>
                <option value="vacataire">Vacataire</option>
            </select>
            <select id="departementFilter" onchange="filterTable()" class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
                <option value="">Tous départements</option>
                @foreach($departements ?? [] as $dep)
                    <option value="{{ strtolower($dep->lib_dep) }}">{{ $dep->lib_dep }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tableau -->
        <div class="table-container">
            <table id="teachersTable" class="min-w-full">
                <thead>
                    <tr>
                        <th>Enseignant</th>
                        <th>Grade</th>
                        <th>Statut</th>
                        <th>Département</th>
                        <th>Taux horaire</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enseignants ?? [] as $ens)
                    <tr data-grade="{{ strtolower($ens->grade?->lib_grd ?? '') }}"
                        data-statut="{{ strtolower($ens->statut?->lib_stat ?? '') }}"
                        data-departement="{{ strtolower($ens->departement?->lib_dep ?? '') }}">
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#5B2E8E] to-[#7C3AED] flex items-center justify-center text-white font-semibold text-sm">
                                    {{ $ens->initiales }}
                                </div>
                                <div>
                                    <div class="font-medium text-sm text-gray-800">{{ $ens->nom_complet }}</div>
                                    <div class="text-xs text-gray-400">{{ $ens->utilisateur?->email ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                        <td><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#5B2E8E]/10 text-[#5B2E8E]">{{ $ens->grade?->lib_grd ?? '—' }}</span></div>
                        <td>
                            @if(strtolower($ens->statut?->lib_stat ?? '') === 'permanent')
                                <span class="badge badge-success">Permanent</span>
                            @else
                                <span class="badge badge-warning">Vacataire</span>
                            @endif
                        </div>
                        <td><span class="text-sm text-gray-600">{{ $ens->departement?->lib_dep ?? '—' }}</span></div>
                        <td><span class="font-semibold text-gray-800">{{ number_format($ens->tx_horaire, 0) }} FCFA</span></div>
                        <td class="text-right">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('secretaire.enseignants.edit', $ens) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('secretaire.enseignants.destroy', $ens) }}" onsubmit="return confirm('Supprimer cet enseignant ?')" class="inline">
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
                    <tr><td colspan="6" class="text-center py-8 text-gray-400">Aucun enseignant</div></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enseignants->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100">
            {{ $enseignants->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function filterTable() {
    const grade = document.getElementById('gradeFilter')?.value.toLowerCase() || '';
    const status = document.getElementById('statusFilter')?.value.toLowerCase() || '';
    const departement = document.getElementById('departementFilter')?.value.toLowerCase() || '';
    
    const rows = document.querySelectorAll('#teachersTable tbody tr');
    rows.forEach(row => {
        const gradeVal = row.dataset.grade || '';
        const statutVal = row.dataset.statut || '';
        const departementVal = row.dataset.departement || '';
        
        const matchGrade = !grade || gradeVal.includes(grade);
        const matchStatus = !status || statutVal.includes(status);
        const matchDepartement = !departement || departementVal.includes(departement);
        
        row.style.display = (matchGrade && matchStatus && matchDepartement) ? '' : 'none';
    });
}
</script>
@endpush
@endsection