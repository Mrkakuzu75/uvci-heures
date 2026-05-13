@extends('layouts.app')
@section('title','Mon espace')
@section('sidebar-role','Enseignant')
@section('page-title','Mon espace pédagogique')
@section('page-subtitle','Consultez vos activités et volumes horaires')

@section('sidebar-nav')
  <x-nav-item route="enseignant.dashboard" label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="enseignant.activites"  label="Mes activités"  icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
@endsection

@section('content')

{{-- BANNER PROFIL --}}
<div style="background:linear-gradient(135deg,#0D1B2A 0%,#1A3A5C 100%);border-radius:16px;padding:28px;margin-bottom:20px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:#00C07F;border-radius:50%;opacity:.06;"></div>
  <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:20px;position:relative;">
    <div style="display:flex;align-items:center;gap:18px;">
      <div style="width:60px;height:60px;border-radius:16px;background:#00C07F;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:22px;color:#0D1B2A;border:3px solid rgba(255,255,255,.15);flex-shrink:0;">
        {{ $enseignant->initiales }}
      </div>
      <div>
        <div style="font-weight:700;font-size:20px;color:#fff;margin-bottom:6px;">{{ $enseignant->nom_complet }}</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
          <span style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.75);font-size:12px;padding:3px 10px;border-radius:20px;">{{ $enseignant->grade?->lib_grd }}</span>
          <span style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.75);font-size:12px;padding:3px 10px;border-radius:20px;">{{ $enseignant->departement?->lib_dep }}</span>
          @if(strtolower($enseignant->statut?->lib_stat??'') === 'permanent')
          <span style="background:rgba(0,192,127,.2);color:#00C07F;font-size:12px;padding:3px 10px;border-radius:20px;">Permanent</span>
          @else
          <span style="background:rgba(255,107,53,.2);color:#FF6B35;font-size:12px;padding:3px 10px;border-radius:20px;">Vacataire</span>
          @endif
        </div>
      </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="{{ route('enseignant.activites') }}" class="btn" style="background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.2);">
        Mes activités →
      </a>
      <a href="{{ route('enseignant.recapitulatif', ['annee_id'=>$annee?->id_anee]) }}"
         target="_blank"
         class="btn" style="background:#00C07F;color:#0D1B2A;font-weight:600;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Télécharger récapitulatif
      </a>
    </div>
  </div>

  {{-- Statistiques dans le banner --}}
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:0;margin-top:24px;border-top:1px solid rgba(255,255,255,.1);padding-top:20px;">
    <div style="padding:0 20px 0 0;">
      <div style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">Volume total</div>
      <div style="font-weight:700;font-size:24px;color:#fff;">{{ number_format($volumeTotal,1) }}<span style="font-size:13px;font-weight:400;color:rgba(255,255,255,.5);margin-left:3px;">h</span></div>
    </div>
    <div style="padding:0 20px;border-left:1px solid rgba(255,255,255,.1);">
      <div style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">Heures complémentaires</div>
      <div style="font-weight:700;font-size:24px;color:{{ $heuresComplementaires > 0 ? '#FF6B35' : '#fff' }};">{{ number_format($heuresComplementaires,1) }}<span style="font-size:13px;font-weight:400;color:rgba(255,255,255,.5);margin-left:3px;">h</span></div>
    </div>
    <div style="padding:0 20px;border-left:1px solid rgba(255,255,255,.1);">
      <div style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">Activités</div>
      <div style="font-weight:700;font-size:24px;color:#5BA4F5;">{{ $activites->count() }}</div>
    </div>
    <div style="padding:0 0 0 20px;border-left:1px solid rgba(255,255,255,.1);">
      <div style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;">Taux horaire</div>
      <div style="font-weight:700;font-size:24px;color:#fff;">{{ number_format($enseignant->tx_horaire,0) }}<span style="font-size:11px;font-weight:400;color:rgba(255,255,255,.5);margin-left:3px;">FCFA/h</span></div>
    </div>
  </div>
</div>

{{-- ACTIVITÉS RÉCENTES --}}
<div class="card">
  <div class="card-header">
    <h3>Activités récentes</h3>
    <a href="{{ route('enseignant.activites') }}" style="font-size:12px;font-weight:500;color:var(--green-dark);text-decoration:none;">Tout voir →</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Cours / Ressource</th>
          <th class="hide-mobile">Type</th>
          <th class="hide-mobile">Niveau</th>
          <th>Volume (h)</th>
        </tr>
      </thead>
      <tbody>
        @forelse($activites as $act)
        <tr>
          <td style="color:var(--muted);white-space:nowrap;">{{ $act->date_act->format('d/m/Y') }}</td>
          <td>
            <div style="font-size:13.5px;font-weight:500;">{{ $act->ressource?->sequence?->cours?->intit ?? '—' }}</div>
            <div style="font-size:11px;color:var(--muted);">{{ $act->ressource?->sequence?->ttre_seq ?? '' }}</div>
          </td>
          <td class="hide-mobile">
            @if($act->id_typ_act == 1)
              <span class="badge-green">Création</span>
            @else
              <span class="badge-orange">Mise à jour</span>
            @endif
          </td>
          <td class="hide-mobile">
            <span style="display:inline-flex;width:28px;height:28px;border-radius:8px;background:#F4F6FA;align-items:center;justify-content:center;font-weight:700;font-size:13px;">N{{ $act->ressource?->niv_comp ?? '?' }}</span>
          </td>
          <td style="font-weight:700;font-size:14px;color:var(--green);">{{ number_format($act->v_hor,2) }}h</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted);">Aucune activité enregistrée</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
