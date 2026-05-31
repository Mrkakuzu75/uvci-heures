@extends('layouts.admin')
@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec bouton Ajouter (style identique à la section utilisateurs) -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Tableau de bord</h1>
            <p class="text-gray-500 mt-1">Vue d'ensemble du système de gestion des heures</p>
        </div>
        <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter un utilisateur
        </a>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        @php
            $kpis = [
                ['label' => 'Enseignants', 'value' => $stats['total_enseignants'] ?? 0, 'color' => '#5B2E8E', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Utilisateurs', 'value' => $stats['total_utilisateurs'] ?? 0, 'color' => '#2E7D32', 'icon' => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2 M9 7a4 4 0 100-8 4 4 0 000 8z M23 21v-2a4 4 0 00-3-3.87 M16 3.13a4 4 0 010 7.75'],
                ['label' => 'Activités', 'value' => $stats['total_activites'] ?? 0, 'color' => '#F59E0B', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ['label' => 'Volume total', 'value' => number_format($stats['volume_total'] ?? 0, 1) . 'h', 'color' => '#3B82F6', 'icon' => 'M12 8v4l3 3M12 8v4l-3 3'],
            ];
        @endphp
        @foreach($kpis as $k)
        <div class="kpi-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="kpi-value" style="color: #1f2937;">{{ $k['value'] }}</div>
                    <div class="kpi-label text-gray-500">{{ $k['label'] }}</div>
                </div>
                <div class="kpi-icon" style="background: {{ $k['color'] }}10; color: {{ $k['color'] }}">
                    @if($k['label'] == 'Volume total')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3.5 3.5"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $k['icon'] }}"/>
                        </svg>
                    @endif
                </div>
            </div>
            <div class="kpi-bar mt-3">
                <div class="kpi-fill" style="background: {{ $k['color'] }}; width: 65%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Grille 2 colonnes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top enseignants -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-gray-800">Top enseignants</h3>
                <span class="text-xs text-gray-400">{{ $stats['annee']?->lib_anee ?? 'Année en cours' }}</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($enseignantsActifs ?? [] as $ens)
                <div class="flex items-center gap-3 px-5 py-3">
                    <div class="avatar avatar-purple">{{ $ens->initiales }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-800 truncate">{{ $ens->nom_complet }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ $ens->grade?->lib_grd }}</div>
                    </div>
                    <div class="font-bold text-sm text-gray-700 shrink-0">{{ number_format($ens->volume_horaire ?? 0, 1) }}h</div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">Aucune activité enregistrée</div>
                @endforelse
            </div>
        </div>

        <!-- Année active -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-gray-800">Année académique active</h3>
            </div>
            <div class="p-5">
                @php $activeYear = $stats['annee'] ?? null; @endphp
                @if($activeYear)
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-xl text-gray-800">{{ $activeYear->lib_anee }}</div>
                        <div class="text-sm text-gray-500">{{ $activeYear->dte_dbut->format('d/m/Y') }} → {{ $activeYear->dte_fn->format('d/m/Y') }}</div>
                    </div>
                </div>
                @else
                <div class="text-center text-gray-400 py-4">
                    <p>Aucune année académique active</p>
                    <a href="{{ route('admin.annees') }}" class="text-sm text-[#5B2E8E] hover:underline mt-2 inline-block">Créer une année →</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Derniers utilisateurs -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Derniers utilisateurs</h3>
            <a href="{{ route('admin.utilisateurs') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Voir tous
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date création</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($utilisateurs ?? [] as $u)
                    <tr>
                        <td><span class="font-medium text-gray-800">{{ $u->login }}</span></td>
                        <td><span class="text-gray-600">{{ $u->email }}</span></td>
                        <td>
                            @if($u->role === 'administrateur')
                                <span class="badge badge-purple">Administrateur</span>
                            @elseif($u->role === 'secretaire')
                                <span class="badge badge-info">Secrétaire</span>
                            @else
                                <span class="badge badge-success">Enseignant</span>
                            @endif
                        </td>
                        <td><span class="text-gray-500 text-sm">{{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : '—' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-6 text-gray-400">Aucun utilisateur</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection