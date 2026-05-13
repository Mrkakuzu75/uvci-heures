@extends('layouts.app')
@section('title','Activités')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','Activités pédagogiques')

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('topbar-actions')
  <a href="{{ route('secretaire.activites.create') }}" class="btn btn-navy">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    <span class="btn-text">Nouvelle activité</span>
  </a>
@endsection

@section('content')

{{-- Résumé --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
  <div>
    <span style="font-size:15px;font-weight:600;color:var(--navy);">Suivi des activités</span>
    <span style="font-size:13px;color:var(--muted);margin-left:8px;">
      — {{ $annee?->lib_anee ?? 'Toutes les années' }}
    </span>
  </div>
  <div style="background:var(--green-light);border:1px solid rgba(0,192,127,.3);border-radius:10px;padding:8px 16px;">
    <span style="font-size:12px;color:var(--green-dark);font-weight:500;">Volume total : </span>
    <span style="font-size:15px;font-weight:700;color:var(--green-dark);">{{ number_format($activites->sum('v_hor'),1) }}h</span>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3>{{ $activites->total() }} activité(s)</h3>
    <a href="{{ route('secretaire.activites.create') }}" class="btn btn-green btn-sm">+ Nouvelle activité</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Enseignant</th>
          <th class="hide-mobile">Cours / Ressource</th>
          <th class="hide-mobile">Type</th>
          <th>Vol. (h)</th>
        </tr>
      </thead>
      <tbody>
        @forelse($activites as $act)
        <tr>
          <td style="color:var(--muted);white-space:nowrap;font-size:13px;">{{ $act->date_act->format('d/m/Y') }}</td>
          <td style="font-weight:500;font-size:13.5px;">{{ $act->enseignant?->nom_complet ?? '—' }}</td>
          <td class="hide-mobile">
            <div style="font-size:13px;font-weight:500;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
              {{ $act->ressource?->sequence?->cours?->intit ?? '—' }}
            </div>
            <div style="font-size:11px;color:var(--muted);">Niveau {{ $act->ressource?->niv_comp ?? '?' }}</div>
          </td>
          <td class="hide-mobile">
            @if($act->id_typ_act == 1)
              <span class="badge-green">Création</span>
            @else
              <span class="badge-orange">Mise à jour</span>
            @endif
          </td>
          <td style="font-weight:700;font-size:14px;color:var(--green);white-space:nowrap;">{{ number_format($act->v_hor,2) }}h</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted);">Aucune activité enregistrée</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($activites->hasPages())
  <div style="padding:14px 20px;border-top:1px solid var(--border);">{{ $activites->links() }}</div>
  @endif
</div>
@endsection
