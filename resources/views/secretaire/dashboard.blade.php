@extends('layouts.admin')
@section('title', 'Secrétaire - Tableau de bord')

@section('content')
<div class="space-y-6">
    {{-- ══ EN-TÊTE ══════════════════════════════════════════════════════ --}}
    <div class="flex justify-between items-center flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Tableau de bord</h1>
            <p class="text-gray-500 mt-1">Gestion des enseignants et activités pédagogiques</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('secretaire.statistiques.pdf', ['annee_id'=>$annee?->id_anee ?? '']) }}"
               target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a3 3 0 013-3V9a3 3 0 10-6 0v3a3 3 0 013 3v2m4-3V4a3 3 0 10-6 0v10m6 0h-6m6 0v3a3 3 0 01-3 3H9a3 3 0 01-3-3v-3"/>
                </svg>
                Statistiques
            </a>
            <a href="{{ route('secretaire.activites.create') }}" class="btn btn-primary">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle activité
            </a>
        </div>
    </div>

    {{-- ══ KPI ══════════════════════════════════════════════════════════ --}}
    <div class="kpi-grid">
        @php
            $kpis = [
                ['label'=>'Enseignants', 'value'=>$stats['total_enseignants'] ?? 0, 'color'=>'#5B2E8E',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'],
                ['label'=>'Cours', 'value'=>$stats['total_cours'] ?? 0, 'color'=>'#2E7D32',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'],
                ['label'=>'Activités validées', 'value'=>$stats['total_activites'] ?? 0, 'color'=>'#F59E0B',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['label'=>'Volume total (h)', 'value'=>number_format($stats['volume_total'] ?? 0, 1), 'color'=>'#3B82F6',
                 'icon'=>'<circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3.5 3.5"/>'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="kpi-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="kpi-value" style="color:#1f2937;">{{ $kpi['value'] }}</div>
                    <div class="kpi-label text-gray-500">{{ $kpi['label'] }}</div>
                </div>
                <div class="kpi-icon" style="background: {{ $kpi['color'] }}10; color: {{ $kpi['color'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        {!! $kpi['icon'] !!}
                    </svg>
                </div>
            </div>
            <div class="kpi-bar mt-3">
                <div class="kpi-fill" style="background: {{ $kpi['color'] }}; width: 65%"></div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ ALERTE DÉPASSEMENT ════════════════════════════════════════════ --}}
    @php
        $seuil = $seuil ?? 192;
        $enseignantsDepasses = isset($enseignants) ? $enseignants->getCollection()->filter(fn($e) => ($e->volume_horaire ?? 0) > $seuil) : collect();
        $nbDepasses = $enseignantsDepasses->count();
    @endphp

    @if($nbDepasses > 0)
    <div class="bg-orange-50 border border-orange-200 border-l-4 border-l-orange-500 rounded-xl p-5">
        <div class="flex items-start gap-3 mb-4">
            <svg class="w-5 h-5 text-orange-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <div>
                <div class="font-bold text-sm text-orange-800">
                    {{ $nbDepasses }} enseignant(s) ont dépassé la charge de {{ $seuil }}h
                </div>
                <div class="text-xs text-orange-600 mt-0.5">
                    Les heures au-delà de {{ $seuil }}h sont majorées à 150% du taux horaire
                </div>
            </div>
        </div>
        <div class="space-y-2">
            @foreach($enseignantsDepasses as $ens)
            @php
                $vol = (float)($ens->volume_horaire ?? 0);
                $compl = round($vol - $seuil, 1);
                $pct = round($vol / $seuil * 100);
            @endphp
            <div class="bg-white rounded-lg p-3 flex items-center gap-3 flex-wrap">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold text-sm shrink-0">
                    {{ $ens->initiales ?? substr($ens->nom_complet, 0, 2) }}
                </div>
                <div class="flex-1 min-w-[150px]">
                    <div class="font-semibold text-sm text-gray-800">{{ $ens->nom_complet }}</div>
                    <div class="text-xs text-gray-500">{{ $ens->grade?->lib_grd ?? '' }} — {{ $ens->departement?->lib_dep ?? '' }}</div>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-center">
                        <div class="text-lg font-bold text-orange-600">{{ number_format($vol, 1) }}h</div>
                        <div class="text-xs text-gray-400">Volume total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-orange-800">+{{ $compl }}h</div>
                        <div class="text-xs text-gray-400">Complémentaires</div>
                    </div>
                    <div class="text-center">
                        <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pct }}%</span>
                    </div>
                </div>
                <div class="w-full">
                    <div class="h-1.5 bg-orange-100 rounded-full overflow-hidden flex">
                        <div class="h-full bg-green-500 rounded-l-full" style="width: {{ min(round($seuil/$vol*100), 100) }}%"></div>
                        <div class="h-full bg-orange-500 rounded-r-full" style="width: {{ round($compl/$vol*100) }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-green-700 font-medium">
                Aucun enseignant n'a dépassé la charge de {{ $seuil }}h pour {{ $annee?->lib_anee ?? 'cette année' }}
            </div>
        </div>
    </div>
    @endif

    {{-- ══ RÉPARTITION DES ACTIVITÉS ════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Par type d'activité --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-gray-800">Par type d'activité</h3>
            </div>
            <div class="p-5">
                @php
                    $repartitionTypes = $repartitionTypes ?? collect();
                    $totalVol = $repartitionTypes->sum('volume_total') ?: 1;
                    $tc = ['#5B2E8E', '#22C55E'];
                @endphp
                @if($repartitionTypes->isEmpty())
                    <p class="text-gray-500 text-sm text-center py-4">Aucune activité</p>
                @else
                    <div class="flex items-center gap-5 flex-wrap">
                        <div class="relative w-24 h-24 shrink-0">
                            <svg width="100" height="100" viewBox="0 0 100 100">
                                @php $a = -90; @endphp
                                @foreach($repartitionTypes as $ti => $t)
                                    @php $p = $t->volume_total/$totalVol; $sw = $p*360; $r = 38; $c = 2*M_PI*$r; $d = $p*$c; $o = -($a/360)*$c; @endphp
                                    <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="{{ $tc[$ti%2] }}" stroke-width="16"
                                            stroke-dasharray="{{ $d }} {{ $c }}" stroke-dashoffset="{{ $o }}"/>
                                    @php $a += $sw; @endphp
                                @endforeach
                                <circle cx="50" cy="50" r="22" fill="white"/>
                                <text x="50" y="47" text-anchor="middle" style="font-size:10px;font-weight:700;fill:#1F2937;">{{ number_format($totalVol, 0) }}</text>
                                <text x="50" y="59" text-anchor="middle" style="font-size:8px;fill:#6B7280;">heures</text>
                            </svg>
                        </div>
                        <div class="flex-1">
                            @foreach($repartitionTypes as $ti => $t)
                            <div class="mb-3">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full" style="background:{{ $tc[$ti%2] }}"></span>
                                        <span class="text-xs text-gray-700">{{ $t->lib_typ_act }}</span>
                                    </div>
                                    <span class="text-xs font-bold">{{ number_format($t->volume_total, 1) }}h</span>
                                </div>
                                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="background:{{ $tc[$ti%2] }};width:{{ round($t->volume_total/$totalVol*100) }}%"></div>
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $t->nb_activites }} activité(s) — {{ round($t->volume_total/$totalVol*100) }}%</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Par niveau de complexité --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-gray-800">Par niveau de complexité</h3>
            </div>
            <div class="p-5">
                @php
                    $repartitionNiveaux = $repartitionNiveaux ?? collect();
                    $maxN = $repartitionNiveaux->max('volume_total') ?: 1;
                    $nc = [1=>'#5B2E8E', 2=>'#22C55E', 3=>'#F59E0B'];
                    $nd = [1=>'Contenus simples + quiz', 2=>'Niv.1 + interactifs', 3=>'Serious games'];
                @endphp
                @if($repartitionNiveaux->isEmpty())
                    <p class="text-gray-500 text-sm text-center py-4">Aucune activité</p>
                @else
                    <div class="space-y-3">
                        @foreach($repartitionNiveaux as $niv)
                        @php $p2 = round($niv->volume_total/$maxN*100); @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg inline-flex items-center justify-center font-bold text-xs"
                                          style="background:{{ ($nc[$niv->niv_comp]??'#ccc') }}20;border:1px solid {{ ($nc[$niv->niv_comp]??'#ccc') }}50;color:{{ $nc[$niv->niv_comp]??'#ccc' }}">
                                        N{{ $niv->niv_comp }}
                                    </span>
                                    <div>
                                        <div class="text-sm font-medium text-gray-800">Niveau {{ $niv->niv_comp }}</div>
                                        <div class="text-xs text-gray-400">{{ $nd[$niv->niv_comp] ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold">{{ number_format($niv->volume_total, 1) }}h</div>
                                    <div class="text-xs text-gray-400">{{ $niv->nb_activites }} acte(s)</div>
                                </div>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="background:{{ $nc[$niv->niv_comp]??'#ccc' }};width:{{ $p2 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ VOLUME PAR DÉPARTEMENT ═══════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Volume horaire par département</h3>
            <span class="text-xs text-gray-500">{{ $volumeParDepartement->count() ?? 0 }} département(s)</span>
        </div>
        @php
            $volumeParDepartement = $volumeParDepartement ?? collect();
            $maxDep = $volumeParDepartement->max('volume_total') ?: 1;
            $totDep = $volumeParDepartement->sum('volume_total') ?: 1;
            $dc = ['#5B2E8E', '#22C55E', '#F59E0B', '#3B82F6', '#10B981', '#EF4444'];
        @endphp
        @if($volumeParDepartement->isEmpty())
            <div class="p-8 text-center text-gray-500">Aucune donnée</div>
        @else
            <div class="p-5 space-y-3">
                @foreach($volumeParDepartement as $di => $dep)
                @php $pd = $maxDep > 0 ? round($dep->volume_total/$maxDep*100) : 0; @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5 gap-3 flex-wrap">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $dc[$di%6] }}"></span>
                            <span class="text-sm font-medium text-gray-800 truncate">{{ $dep->lib_dep }}</span>
                        </div>
                        <div class="flex items-center gap-4 shrink-0 text-xs text-gray-500">
                            <span class="whitespace-nowrap">{{ $dep->nb_enseignants }} ens.</span>
                            <span class="whitespace-nowrap">{{ $dep->nb_activites }} act.</span>
                            <span class="text-sm font-bold text-gray-800 whitespace-nowrap">{{ number_format($dep->volume_total, 1) }}h</span>
                        </div>
                    </div>
                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="background:{{ $dc[$di%6] }};width:{{ $pd }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="table-container border-t border-gray-100">
                <table class="min-w-full">
                    <thead>
                        <tr><th>Département</th><th class="text-center">Enseignants</th><th class="text-center">Activités</th><th class="text-right">Volume (h)</th><th class="text-right">Part (%)</th></tr>
                    </thead>
                    <tbody>
                        @foreach($volumeParDepartement as $di => $dep)
                        <tr>
                            <td><div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full" style="background:{{ $dc[$di%6] }}"></span><span>{{ $dep->lib_dep }}</span></div></td>
                            <td class="text-center">{{ $dep->nb_enseignants }}</td>
                            <td class="text-center">{{ $dep->nb_activites }}</td>
                            <td class="text-right font-bold" style="color:{{ $dc[$di%6] }}">{{ number_format($dep->volume_total, 1) }}h</div></td>
                            <td class="text-right text-gray-500">{{ round($dep->volume_total/$totDep*100, 1) }}%</div></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-green-50">
                        <tr>
                            <td class="font-bold">TOTAL</div></td>
                            <td class="text-center font-bold">{{ $volumeParDepartement->sum('nb_enseignants') }}</div></td>
                            <td class="text-center font-bold">{{ $volumeParDepartement->sum('nb_activites') }}</div></td>
                            <td class="text-right font-bold text-green-700">{{ number_format($volumeParDepartement->sum('volume_total'), 1) }}h</div></td>
                            <td class="text-right font-bold">100%</div></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- ══ STATISTIQUES MENSUELLES ═══════════════════════ --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Statistiques mensuelles</h3>
            <span class="text-xs text-gray-500">{{ $annee?->lib_anee ?? 'Année en cours' }}</span>
        </div>
        <div class="p-5">
            @php 
                $moisNoms = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
                $statsMensuelles = $statsMensuelles ?? collect();
                $volumes = $statsMensuelles->pluck('volume')->toArray();
                $maxVol = !empty($volumes) ? max($volumes) : 1;
                $totalVolume = array_sum($volumes);
            @endphp

            {{-- Mini KPIs mensuels --}}
            <div class="grid grid-cols-3 gap-4 mb-6 pb-4 border-b border-gray-100">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($totalVolume, 1) }}</div>
                    <div class="text-xs text-gray-400">Volume total (h)</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $statsMensuelles->where('volume', '>', 0)->count() }}</div>
                    <div class="text-xs text-gray-400">Mois actifs</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $statsMensuelles->avg('volume') ? number_format($statsMensuelles->avg('volume'), 1) : '0' }}</div>
                    <div class="text-xs text-gray-400">Moyenne (h/mois)</div>
                </div>
            </div>

            {{-- Graphique en barres --}}
            <div class="mb-6">
                <div class="flex items-end gap-1 h-48">
                    @foreach($statsMensuelles as $m)
                        @php 
                            $hauteur = $maxVol > 0 ? ($m['volume'] / $maxVol) * 100 : 0;
                            $couleur = $m['volume'] > 0 ? '#5B2E8E' : '#E5E7EB';
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1 group">
                            <div class="relative w-full flex justify-center">
                                <div class="absolute -top-6 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded px-1.5 py-0.5 whitespace-nowrap">
                                    {{ number_format($m['volume'], 1) }}h
                                </div>
                                <div class="w-full max-w-[40px] bg-gray-100 rounded-t-lg transition-all duration-300 hover:bg-opacity-80" 
                                     style="height: {{ $hauteur }}px; background-color: {{ $couleur }};">
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 mt-2">{{ $moisNoms[$m['mois']-1] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tableau détaillé --}}
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Mois</th>
                            <th class="text-right">Activités</th>
                            <th class="text-right">Volume (h)</th>
                            <th class="text-right w-32">Progression</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statsMensuelles as $m)
                        <tr class="border-t border-gray-100">
                            <td class="font-medium text-gray-800 py-2">{{ $moisNoms[$m['mois']-1] }}</td>
                            <td class="text-right text-gray-600">{{ $m['nb_activites'] }}</td>
                            <td class="text-right font-semibold text-gray-800">{{ number_format($m['volume'], 1) }}h</div></td>
                            <td class="text-right">
                                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    @php $barWidth = $maxVol > 0 ? round($m['volume'] / $maxVol * 100) : 0; @endphp
                                    <div class="h-full rounded-full bg-[#5B2E8E]" style="width: {{ $barWidth }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8 text-gray-400">Aucune donnée</div></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ TABLE ENSEIGNANTS ═══════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">{{ $enseignants->total() ?? 0 }} enseignant(s)</h3>
            <a href="{{ route('secretaire.enseignants.create') }}" class="btn btn-primary">+ Ajouter</a>
        </div>

        <div class="p-4 border-b border-gray-100 flex gap-2.5 flex-wrap">
            <div class="relative flex-1 min-w-[160px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Rechercher un enseignant..."
                       class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]">
            </div>
            <select id="statusFilter" onchange="filterTable()" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#5B2E8E]/20 focus:border-[#5B2E8E]">
                <option value="">Tous statuts</option>
                <option value="permanent">Permanent</option>
                <option value="vacataire">Vacataire</option>
            </select>
        </div>

        <div class="table-container">
            <table id="teachersTable" class="min-w-full">
                <thead>
                    <tr><th>Enseignant</th><th>Grade</th><th class="hide-mobile">Statut</th><th class="hide-mobile">Département</th><th class="text-right">Volume (h)</th><th class="text-right">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($enseignants ?? [] as $ens)
                    @php $vol = (float)($ens->volume_horaire ?? 0); $depasse = $vol > ($seuil ?? 192); @endphp
                    <tr data-statut="{{ strtolower($ens->statut?->lib_stat ?? '') }}" data-nom="{{ strtolower($ens->nom_complet) }}" class="{{ $depasse ? 'bg-orange-50' : '' }}">
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-semibold text-sm shrink-0 bg-gradient-to-br from-[#5B2E8E] to-[#7C3AED] text-white">
                                    {{ $ens->initiales ?? substr($ens->nom_complet, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-medium text-sm text-gray-800">{{ $ens->nom_complet }}</div>
                                    <div class="text-xs text-gray-400">{{ $ens->utilisateur?->email ?? '—' }}</div>
                                </div>
                            </div>
                         </div>
                        <td><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#5B2E8E]/10 text-[#5B2E8E]">{{ $ens->grade?->lib_grd ?? '—' }}</span></div>
                        <td class="hide-mobile">@if(strtolower($ens->statut?->lib_stat ?? '') == 'permanent')<span class="badge badge-success">Permanent</span>@else<span class="badge badge-warning">Vacataire</span>@endif</div>
                        <td class="hide-mobile text-sm text-gray-500">{{ $ens->departement?->lib_dep ?? '—' }}</div>
                        <td class="text-right"><span class="text-sm font-bold {{ $depasse ? 'text-orange-600' : 'text-gray-800' }}">{{ number_format($vol, 1) }}h</span>@if($depasse)<div class="text-xs text-orange-500">+{{ round($vol - ($seuil ?? 192), 1) }}h</div>@endif</div>
                        <td class="text-right">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('secretaire.enseignants.edit', $ens) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('secretaire.enseignants.destroy', $ens) }}" onsubmit="return confirm('Supprimer ?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Suppr.
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

        @if(isset($enseignants) && $enseignants->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100">
            {{ $enseignants->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function filterTable() {
    const search = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const status = document.getElementById('statusFilter')?.value.toLowerCase() || '';
    const rows = document.querySelectorAll('#teachersTable tbody tr[data-nom]');
    rows.forEach(row => {
        const nom = row.dataset.nom || '';
        const statut = row.dataset.statut || '';
        const matchSearch = !search || nom.includes(search);
        const matchStatus = !status || statut.includes(status);
        row.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
}
</script>
@endpush
@endsection