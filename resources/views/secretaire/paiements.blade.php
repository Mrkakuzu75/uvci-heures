@extends('layouts.app')
@section('title','États de paiement')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','États de paiement')
@section('page-subtitle','Récapitulatif des volumes et montants à payer')

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('topbar-actions')
  {{-- Filtre année --}}
  <form method="GET" action="{{ route('secretaire.paiements') }}" style="display:flex;align-items:center;gap:8px;">
    <select name="annee_id" onchange="this.form.submit()"
      style="padding:7px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#fff;outline:none;cursor:pointer;color:var(--navy);font-family:inherit;">
      @foreach($annees as $a)
      <option value="{{ $a->id_anee }}" {{ $annee?->id_anee == $a->id_anee ? 'selected' : '' }}>
        {{ $a->lib_anee }}{{ $a->etat_anee==='en_cours' ? ' ✓' : '' }}
      </option>
      @endforeach
    </select>
  </form>
  @if($annee)
  <a href="{{ route('secretaire.paiements.pdf-global', ['annee_id'=>$annee->id_anee]) }}"
     target="_blank"
     class="btn btn-navy">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
    <span class="btn-text">Imprimer état global</span>
  </a>
  @endif
@endsection

@section('content')

@if(!$annee)
  <div style="text-align:center;padding:60px;color:var(--muted);">
    <div style="font-size:48px;margin-bottom:16px;">📋</div>
    <div style="font-weight:600;font-size:16px;margin-bottom:8px;">Aucune année académique active</div>
    <div style="font-size:13px;">Configurez une année académique dans les paramètres.</div>
  </div>
@else

{{-- KPI TOTAUX --}}
<div class="kpi-grid" style="margin-bottom:20px;">
  <div class="kpi-card">
    <div class="kpi-icon">👨‍🏫</div>
    <div class="kpi-value">{{ $totaux['nb_ens'] }}</div>
    <div class="kpi-label">Enseignants actifs</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#00C07F;width:70%"></div></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon">⏱️</div>
    <div class="kpi-value">{{ number_format($totaux['volume'],1) }}h</div>
    <div class="kpi-label">Volume horaire total</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#4A90E2;width:65%"></div></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon">💰</div>
    <div class="kpi-value">{{ number_format($totaux['montant'],0,'.',' ') }}</div>
    <div class="kpi-label">Montant total (FCFA)</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#9B59B6;width:55%"></div></div>
  </div>
</div>

{{-- TABLE ÉTAT DE PAIEMENT --}}
<div class="card">
  <div class="card-header">
    <h3>État de paiement — {{ $annee->lib_anee }}</h3>
    <span style="font-size:12px;color:var(--muted);">Heures complémentaires majorées à 150%</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Enseignant</th>
          <th class="hide-mobile">Grade</th>
          <th style="text-align:right;">Vol. total</th>
          <th style="text-align:right;" class="hide-mobile">H. normal.</th>
          <th style="text-align:right;" class="hide-mobile">H. complém.</th>
          <th style="text-align:right;">Taux (FCFA)</th>
          <th style="text-align:right;">Montant total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @php $avc=['av-green','av-blue','av-purple','av-orange','av-teal']; @endphp
        @forelse($etat as $idx => $ligne)
        @php $ens = $ligne['enseignant']; @endphp
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar {{ $avc[$idx % 5] }}">{{ $ens->initiales }}</div>
              <div>
                <div style="font-weight:500;font-size:13.5px;">{{ $ens->nom_complet }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $ens->departement?->lib_dep ?? '—' }}</div>
              </div>
            </div>
          </td>
          <td class="hide-mobile"><span class="badge-green">{{ $ens->grade?->lib_grd ?? '—' }}</span></td>
          <td style="text-align:right;font-weight:700;font-size:14px;">
            {{ number_format($ligne['volume_total'],1) }}h
          </td>
          <td style="text-align:right;font-size:13px;color:var(--muted);" class="hide-mobile">
            {{ number_format($ligne['heures_normales'],1) }}h
          </td>
          <td style="text-align:right;" class="hide-mobile">
            @if($ligne['heures_complementaires'] > 0)
              <span class="badge-orange">{{ number_format($ligne['heures_complementaires'],1) }}h</span>
            @else
              <span style="color:var(--muted);font-size:13px;">—</span>
            @endif
          </td>
          <td style="text-align:right;font-size:13px;color:var(--muted);">
            {{ number_format($ens->tx_horaire,0) }}
          </td>
          <td style="text-align:right;font-weight:700;font-size:14px;color:var(--green-dark);">
            {{ number_format($ligne['montant_total'],0,'.',' ') }}
          </td>
          <td>
            <a href="{{ route('secretaire.paiements.fiche', [$ens->id_ens, 'annee_id'=>$annee->id_anee]) }}"
               target="_blank"
               class="btn btn-outline btn-sm"
               title="Imprimer fiche individuelle">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
              Fiche
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted);">Aucune activité enregistrée pour cette période</td></tr>
        @endforelse
      </tbody>
      @if(count($etat) > 0)
      <tfoot>
        <tr style="background:#F4F6FA;border-top:2px solid var(--border);">
          <td colspan="2" style="padding:12px 16px;font-weight:600;font-size:13px;">TOTAL GÉNÉRAL</td>
          <td style="text-align:right;font-weight:700;padding:12px 16px;">{{ number_format($totaux['volume'],1) }}h</td>
          <td colspan="3" class="hide-mobile"></td>
          <td style="text-align:right;font-weight:700;font-size:15px;color:var(--green-dark);padding:12px 16px;">
            {{ number_format($totaux['montant'],0,'.',' ') }} FCFA
          </td>
          <td></td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>

@endif
@endsection
