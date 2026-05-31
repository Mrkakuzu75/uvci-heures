@extends('layouts.admin')
@section('title', 'Mes activités')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Mes activités</h1>
            <p class="text-gray-500 mt-1">Consultez l'ensemble de vos activités pédagogiques</p>
        </div>
        <div class="flex gap-2">
            <select id="anneeFilter" onchange="filterByAnnee()" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20">
                <option value="">Toutes les années</option>
                @foreach($annees as $a)
                    <option value="{{ $a->id_anee }}" {{ $anneeId == $a->id_anee ? 'selected' : '' }}>
                        {{ $a->lib_anee }}
                    </option>
                @endforeach
            </select>
            <a href="{{ route('enseignant.recapitulatif', ['annee_id' => $anneeId]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a3 3 0 013-3V9a3 3 0 10-6 0v3a3 3 0 013 3v2m4-3V4a3 3 0 10-6 0v10m6 0h-6m6 0v3a3 3 0 01-3 3H9a3 3 0 01-3-3v-3"/>
                </svg>
                PDF récapitulatif
            </a>
        </div>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="kpi-value" style="color: #1f2937;">{{ $activites->total() }}</div>
                    <div class="kpi-label text-gray-500">Total activités</div>
                </div>
                <div class="kpi-icon" style="background: #5B2E8E10; color: #5B2E8E">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-bar mt-3"><div class="kpi-fill" style="background: #5B2E8E; width: 65%"></div></div>
        </div>

        <div class="kpi-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="kpi-value" style="color: #1f2937;">{{ $activites->where('est_valide', true)->count() }}</div>
                    <div class="kpi-label text-gray-500">Validées</div>
                </div>
                <div class="kpi-icon" style="background: #2E7D3210; color: #2E7D32">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-bar mt-3"><div class="kpi-fill" style="background: #2E7D32; width: 65%"></div></div>
        </div>

        <div class="kpi-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="kpi-value" style="color: #1f2937;">{{ $activites->where('est_valide', false)->count() }}</div>
                    <div class="kpi-label text-gray-500">En attente</div>
                </div>
                <div class="kpi-icon" style="background: #F59E0B10; color: #F59E0B">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-bar mt-3"><div class="kpi-fill" style="background: #F59E0B; width: 65%"></div></div>
        </div>

        <div class="kpi-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="kpi-value" style="color: #1f2937;">{{ number_format($volumeTotal, 0) }}<span class="text-sm text-gray-400 ml-0.5">h</span></div>
                    <div class="kpi-label text-gray-500">Volume total</div>
                </div>
                <div class="kpi-icon" style="background: #3B82F610; color: #3B82F6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-bar mt-3"><div class="kpi-fill" style="background: #3B82F6; width: 65%"></div></div>
        </div>
    </div>

    <!-- Tableau style admin -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Liste détaillée</h3>
            <span class="text-xs text-gray-400">{{ $activites->total() }} activité(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Cours</th>
                        <th>Séquence</th>
                        <th>Type</th>
                        <th class="text-center">Niveau</th>
                        <th class="text-right">Volume</th>
                        <th class="text-center">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activites as $act)
                    <tr class="hover:bg-gray-50">
                        <td class="text-gray-600">{{ $act->date_act->format('d/m/Y') }}</div>
                        <td class="font-medium text-gray-800">{{ $act->ressource?->sequence?->cours?->intit ?? '—' }}</div>
                        <td class="text-gray-500">{{ $act->ressource?->sequence?->ttre_seq ?? '—' }}</div>
                        <td>
                            @if($act->id_typ_act == 1)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#5B2E8E]/10 text-[#5B2E8E]">Création</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Mise à jour</span>
                            @endif
                        </div>
                        <td class="text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-bold text-sm">
                                N{{ $act->ressource?->niv_comp ?? '?' }}
                            </span>
                        </div>
                        <td class="text-right font-semibold text-gray-800">{{ number_format($act->v_hor, 1) }} h</div>
                        <td class="text-center">
                            @if($act->est_valide)
                                <span class="badge badge-success">Validé</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </div>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-400">Aucune activité trouvée</div>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activites->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Affichage de {{ $activites->firstItem() }} à {{ $activites->lastItem() }} sur {{ $activites->total() }}
            </div>
            <div class="flex gap-2">
                @if($activites->onFirstPage())
                    <span class="px-3 py-2 rounded-lg text-sm bg-gray-100 text-gray-400 cursor-not-allowed">Précédent</span>
                @else
                    <a href="{{ $activites->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">Précédent</a>
                @endif

                @if($activites->hasMorePages())
                    <a href="{{ $activites->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">Suivant</a>
                @else
                    <span class="px-3 py-2 rounded-lg text-sm bg-gray-100 text-gray-400 cursor-not-allowed">Suivant</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function filterByAnnee() {
        var anneeId = document.getElementById('anneeFilter').value;
        var url = new URL(window.location.href);
        if (anneeId) {
            url.searchParams.set('annee_id', anneeId);
        } else {
            url.searchParams.delete('annee_id');
        }
        window.location.href = url.toString();
    }
</script>
@endsection