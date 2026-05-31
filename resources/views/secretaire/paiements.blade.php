@extends('layouts.admin')
@section('title', 'États de paiement')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">États de paiement</h1>
            <p class="text-gray-500 mt-1">Génération des états de paiement des enseignants</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('secretaire.paiements.pdf-global', ['annee_id' => $annee?->id_anee ?? '']) }}" target="_blank" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a3 3 0 013-3V9a3 3 0 10-6 0v3a3 3 0 013 3v2m4-3V4a3 3 0 10-6 0v10m6 0h-6m6 0v3a3 3 0 01-3 3H9a3 3 0 01-3-3v-3"/>
                </svg>
                PDF global
            </a>
            <a href="{{ route('secretaire.paiements.excel-global', ['annee_id' => $annee?->id_anee ?? '']) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Excel global
            </a>
        </div>
    </div>

    <!-- Sélection année -->
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-4 flex-wrap">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm text-gray-600">Année académique :</span>
        </div>
        <select id="anneeFilter" onchange="location.href = this.value" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm">
            @foreach($annees as $a)
                <option value="{{ route('secretaire.paiements', ['annee_id' => $a->id_anee]) }}" {{ $annee?->id_anee == $a->id_anee ? 'selected' : '' }}>
                    {{ $a->lib_anee }}
                </option>
            @endforeach
        </select>
        @if($annee)
            <span class="text-xs text-gray-400 ml-auto">Période : {{ $annee->dte_dbut->format('d/m/Y') }} → {{ $annee->dte_fn->format('d/m/Y') }}</span>
        @endif
    </div>

    <!-- Tableau des paiements -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">État des paiements</h3>
            <span class="text-xs text-gray-400">{{ count($etat) }} enseignant(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Enseignant</th>
                        <th>Grade</th>
                        <th>Département</th>
                        <th class="text-right">Volume total (h)</th>
                        <th class="text-right">H. normales</th>
                        <th class="text-right">H. complémentaires</th>
                        <th class="text-right">Montant normal (FCFA)</th>
                        <th class="text-right">Montant compl. (FCFA)</th>
                        <th class="text-right">TOTAL (FCFA)</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etat as $index => $ligne)
                    @php $ens = $ligne['enseignant']; @endphp
                    <tr class="hover:bg-gray-50 transition">
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
                        <td><span class="text-sm text-gray-600">{{ $ens->departement?->lib_dep ?? '—' }}</span></div>
                        <td class="text-right font-semibold text-gray-800">{{ number_format($ligne['volume_total'], 1) }}h</div>
                        <td class="text-right text-gray-600">{{ number_format($ligne['heures_normales'], 1) }}h</div>
                        <td class="text-right {{ $ligne['heures_complementaires'] > 0 ? 'text-orange-600 font-semibold' : 'text-gray-600' }}">
                            {{ number_format($ligne['heures_complementaires'], 1) }}h
                        </div>
                        <td class="text-right text-gray-600">{{ number_format($ligne['montant_normal'], 0) }}</div>
                        <td class="text-right {{ $ligne['montant_complementaire'] > 0 ? 'text-orange-600 font-semibold' : 'text-gray-600' }}">
                            {{ number_format($ligne['montant_complementaire'], 0) }}
                        </div>
                        <td class="text-right font-bold text-gray-800">{{ number_format($ligne['montant_total'], 0) }}</div>
                        <td class="text-right">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('secretaire.paiements.fiche', ['enseignant' => $ens->id_ens, 'annee_id' => $annee?->id_anee]) }}" 
                                   target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a3 3 0 013-3V9a3 3 0 10-6 0v3a3 3 0 013 3v2m4-3V4a3 3 0 10-6 0v10m6 0h-6m6 0v3a3 3 0 01-3 3H9a3 3 0 01-3-3v-3"/>
                                    </svg>
                                    PDF
                                </a>
                                <a href="{{ route('secretaire.paiements.excel-fiche', ['enseignant' => $ens->id_ens, 'annee_id' => $annee?->id_anee]) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-green-50 text-green-700 hover:bg-green-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Excel
                                </a>
                            </div>
                        </div>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-8 text-gray-400">Aucune donnée pour cette année</div></tr>
                    @endforelse
                </tbody>
                @if(count($etat) > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="font-bold text-gray-800">TOTAUX</div>
                        <td class="text-right font-bold text-gray-800">{{ number_format($totaux['volume'], 1) }}h</div>
                        <td class="text-right">—</div>
                        <td class="text-right">—</div>
                        <td class="text-right">—</div>
                        <td class="text-right">—</div>
                        <td class="text-right font-bold text-gray-800">{{ number_format($totaux['montant'], 0) }} FCFA</div>
                        <td class="text-right"></div>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection