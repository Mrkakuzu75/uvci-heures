@extends('layouts.app')
@section('title','Mes activités')
@section('sidebar-role','Enseignant')
@section('page-title','Mes activités')
@section('page-subtitle','Historique de vos activités pédagogiques')

@section('sidebar-nav')
  <x-nav-item route="enseignant.dashboard" label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="enseignant.activites"  label="Mes activités"  icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
@endsection

@section('topbar-actions')
  <form method="GET" action="{{ route('enseignant.activites') }}" style="display:flex;align-items:center;gap:8px;">
    <select name="annee_id" onchange="this.form.submit()" style="padding:7px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#fff;outline:none;cursor:pointer;color:var(--navy);font-family:inherit;">
      @foreach($annees as $a)
      <option value="{{ $a->id_anee }}" {{ $anneeId==$a->id_anee?'selected':'' }}>
        {{ $a->lib_anee }}{{ $a->etat_anee==='en_cours'?' ✓':'' }}
      </option>
      @endforeach
    </select>
  </form>
@endsection

@section('content')

{{-- KPI résumé --}}
<div class="kpi-grid" style="margin-bottom:20px;">
  <div class="kpi-card">
    <div class="kpi-icon">⏱️</div>
    <div class="kpi-value">{{ number_format($volumeTotal,1) }}h</div>
    <div class="kpi-label">Volume horaire total</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#00C07F;width:70%"></div></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon">📋</div>
    <div class="kpi-value">{{ $activites->total() }}</div>
    <div class="kpi-label">Activités enregistrées</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#4A90E2;width:60%"></div></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon">💰</div>
    <div class="kpi-value">{{ number_format($volumeTotal * $enseignant->tx_horaire, 0) }}</div>
    <div class="kpi-label">Estimation FCFA</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#9B59B6;width:50%"></div></div>
  </div>
</div>

{{-- TABLE --}}
<div class="card">
  <div class="card-header">
    <h3>Détail des activités</h3>
    <span style="font-size:13px;font-weight:600;color:var(--green);">Total : {{ number_format($volumeTotal,1) }}h</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Cours</th>
          <th>Séquence</th>
          <th class="hide-mobile">Type</th>
          <th class="hide-mobile">Niv.</th>
          <th>Volume</th>
        </tr>
      </thead>
      <tbody>
        @forelse($activites as $act)
        <tr>
          <td style="color:var(--muted);white-space:nowrap;font-size:13px;">{{ $act->date_act->format('d/m/Y') }}</td>
          <td>
            <div style="font-weight:500;font-size:13px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $act->ressource?->sequence?->cours?->intit ?? '—' }}</div>
          </td>
          <td style="font-size:12px;color:var(--muted);max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $act->ressource?->sequence?->ttre_seq ?? '—' }}</td>
          <td class="hide-mobile">
            @if($act->id_typ_act==1)
              <span class="badge-green">Création</span>
            @else
              <span class="badge-orange">Mise à jour</span>
            @endif
          </td>
          <td class="hide-mobile">
            <span style="display:inline-flex;width:28px;height:28px;border-radius:8px;background:#F4F6FA;align-items:center;justify-content:center;font-weight:700;font-size:13px;">N{{ $act->ressource?->niv_comp ?? '?' }}</span>
          </td>
          <td style="font-weight:700;font-size:14px;color:var(--green);white-space:nowrap;">{{ number_format($act->v_hor,2) }}h</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">Aucune activité pour cette période</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($activites->hasPages())
  <div style="padding:14px 20px;border-top:1px solid var(--border);">{{ $activites->links() }}</div>
  @endif
</div>
@endsection
