@extends('layouts.admin')
@section('title', 'Mon espace enseignant')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Tableau de bord</h1>
            <p class="text-gray-500 mt-1">Bienvenue dans votre espace personnel</p>
        </div>
        <a href="{{ route('enseignant.recapitulatif', ['annee_id' => $annee?->id_anee]) }}" target="_blank" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a3 3 0 013-3V9a3 3 0 10-6 0v3a3 3 0 013 3v2m4-3V4a3 3 0 10-6 0v10m6 0h-6m6 0v3a3 3 0 01-3 3H9a3 3 0 01-3-3v-3"/>
            </svg>
            Télécharger récapitulatif
        </a>
    </div>

    <!-- Carte profil -->
    <div class="relative rounded-2xl overflow-hidden p-6" style="background: linear-gradient(135deg, #1a3a5c 0%, #0f2b44 100%);">
        <div class="relative flex flex-wrap items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center">
                    <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-2xl">
                        {{ $enseignant->initiales }}
                    </div>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $enseignant->nom_complet }}</div>
                    <div class="flex gap-2 mt-2 flex-wrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">{{ $enseignant->grade?->lib_grd }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">{{ $enseignant->departement?->lib_dep }}</span>
                        @if(strtolower($enseignant->statut?->lib_stat ?? '') === 'permanent')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/30 text-green-200">Permanent</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-500/30 text-orange-200">Vacataire</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-white/60 text-sm">Taux horaire</div>
                <div class="text-white font-bold text-2xl">{{ number_format($enseignant->tx_horaire, 0) }} <span class="text-sm">FCFA/h</span></div>
            </div>
        </div>

        <!-- Statistiques rapides - TEXTE PLUS VISIBLE -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-5 border-t border-white/10">
            <div>
                <div class="text-white/70 text-xs uppercase tracking-wide">Volume total</div>
                <div class="text-white font-bold text-2xl">{{ number_format($volumeTotal, 0) }} <span class="text-sm text-white/70">h</span></div>
            </div>
            <div>
                <div class="text-white/70 text-xs uppercase tracking-wide">Heures normales</div>
                <div class="text-white font-bold text-2xl">{{ number_format(min($volumeTotal, 192), 0) }} <span class="text-sm text-white/70">h</span></div>
            </div>
            <div>
                <div class="text-white/70 text-xs uppercase tracking-wide">Heures complémentaires</div>
                <div class="text-orange-300 font-bold text-2xl">{{ number_format($heuresComplementaires, 0) }} <span class="text-sm text-white/70">h</span></div>
            </div>
            <div>
                <div class="text-white/70 text-xs uppercase tracking-wide">Activités</div>
                <div class="text-blue-300 font-bold text-2xl">{{ $activites->count() }}</div>
            </div>
        </div>

        <!-- Barre de progression -->
        @php
            $pourcentage = min(100, round(($volumeTotal / 192) * 100));
            $couleurBarre = $pourcentage > 100 ? 'bg-orange-500' : 'bg-green-500';
        @endphp
        <div class="mt-4">
            <div class="flex justify-between text-sm text-white/80 mb-1">
                <span>Progression vers le seuil (192h)</span>
                <span class="font-semibold">{{ $pourcentage }}%</span>
            </div>
            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full {{ $couleurBarre }} rounded-full" style="width: {{ min($pourcentage, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Dernières activités -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Mes dernières activités</h3>
            <a href="{{ route('enseignant.activites') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Voir toutes
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-gray-600">Date</th>
                        <th class="text-gray-600">Cours</th>
                        <th class="text-gray-600">Type</th>
                        <th class="text-right text-gray-600">Volume</th>
                        <th class="text-gray-600">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activites as $act)
                    <tr class="hover:bg-gray-50">
                        <td class="text-gray-600">{{ $act->date_act->format('d/m/Y') }}</td>
                        <td class="font-medium text-gray-800">{{ $act->ressource?->sequence?->cours?->intit ?? '—' }}</td>
                        <td>
                            @if($act->id_typ_act == 1)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#5B2E8E]/10 text-[#5B2E8E]">Création</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Mise à jour</span>
                            @endif
                        </td>
                        <td class="text-right font-semibold text-gray-800">{{ number_format($act->v_hor, 1) }} h</td>
                        <td>
                            @if($act->est_valide)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Validé
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                                    </svg>
                                    En attente
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-8 text-gray-400">Aucune activité enregistrée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection