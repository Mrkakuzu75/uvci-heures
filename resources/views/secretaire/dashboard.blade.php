@extends('layouts.app')
@section('title','Secrétaire')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','Tableau de bord')
@section('page-subtitle','Gestion des enseignants et activités pédagogiques')

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord"  icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"       icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"             icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"         icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
  <x-nav-item route="secretaire.statistiques.pdf" label="Statistiques" icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
@endsection

@section('topbar-actions')
  <a href="{{ route('secretaire.statistiques.pdf', ['annee_id'=>$annee?->id_anee]) }}"
     target="_blank" class="btn btn-outline">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    <span class="btn-text">Statistiques</span>
  </a>
  <a href="{{ route('secretaire.activites.create') }}" class="btn btn-green">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    <span class="btn-text">Nouvelle activité</span>
  </a>
@endsection

@section('content')

{{-- ══ KPI ══════════════════════════════════════════════════════ --}}
<div class="kpi-grid" style="margin-bottom:24px;">
  @php $kpis=[
    ['label'=>'Enseignants',     'value'=>$stats['total_enseignants'],             'icon'=>'👨‍🏫','color'=>'#00C07F'],
    ['label'=>'Cours',           'value'=>$stats['total_cours'],                   'icon'=>'📚', 'color'=>'#4A90E2'],
    ['label'=>'Activités',       'value'=>$stats['total_activites'],               'icon'=>'📋', 'color'=>'#FF6B35'],
    ['label'=>'Volume total (h)','value'=>number_format($stats['volume_total'],1), 'icon'=>'⏱️', 'color'=>'#9B59B6'],
  ]; @endphp
  @foreach($kpis as $k)
  <div class="kpi-card">
    <div class="kpi-icon">{{ $k['icon'] }}</div>
    <div class="kpi-value">{{ $k['value'] }}</div>
    <div class="kpi-label">{{ $k['label'] }}</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:{{ $k['color'] }};width:65%"></div></div>
  </div>
  @endforeach
</div>



{{-- ══ NAVIGATION RAPIDE ═══════════════════════════════════════ --}}
<div style="display:flex;align-items:center;gap:6px;margin-bottom:20px;flex-wrap:wrap;padding:10px 16px;background:#F4F6FA;border-radius:12px;border:1px solid var(--border);">
  <span style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:1px;text-transform:uppercase;margin-right:4px;">Aller à</span>
  <a href="#section-depassement" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:var(--navy);text-decoration:none;padding:5px 12px;border-radius:8px;background:#fff;border:1px solid var(--border);transition:all .15s;white-space:nowrap;" onmouseover="this.style.borderColor='#FF6B35';this.style.color='#FF6B35'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--navy)'">
    ⚠️ Dépassements
  </a>
  <a href="#section-repartition" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:var(--navy);text-decoration:none;padding:5px 12px;border-radius:8px;background:#fff;border:1px solid var(--border);transition:all .15s;white-space:nowrap;" onmouseover="this.style.borderColor='#00C07F';this.style.color='#009962'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--navy)'">
    📊 Répartition
  </a>
  <a href="#section-departements" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:var(--navy);text-decoration:none;padding:5px 12px;border-radius:8px;background:#fff;border:1px solid var(--border);transition:all .15s;white-space:nowrap;" onmouseover="this.style.borderColor='#4A90E2';this.style.color='#4A90E2'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--navy)'">
    🏢 Départements
  </a>
  <a href="#section-mensuelles" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:var(--navy);text-decoration:none;padding:5px 12px;border-radius:8px;background:#fff;border:1px solid var(--border);transition:all .15s;white-space:nowrap;" onmouseover="this.style.borderColor='#9B59B6';this.style.color='#9B59B6'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--navy)'">
    📅 Mensuel
  </a>
  <a href="#section-enseignants" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:var(--navy);text-decoration:none;padding:5px 12px;border-radius:8px;background:#fff;border:1px solid var(--border);transition:all .15s;white-space:nowrap;" onmouseover="this.style.borderColor='#0D1B2A';this.style.color='#0D1B2A'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--navy)'">
    👨‍🏫 Enseignants
  </a>
</div>

<div id="section-depassement" style="scroll-margin-top:80px;"></div>
{{-- ══ ALERTE DÉPASSEMENT DE CHARGE ════════════════════════════ --}}
@php
  $enseignantsDepasses = $enseignants->getCollection()
    ->filter(fn($e) => ($e->volume_horaire ?? 0) > $seuil);
  $nbDepasses = $enseignantsDepasses->count();
@endphp

@if($nbDepasses > 0)
<div style="margin-bottom:24px;">
  <div style="background:#FFF5E6;border:1px solid #FFB347;border-left:4px solid #FF6B35;border-radius:12px;padding:16px 20px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
      <span style="font-size:20px;">⚠️</span>
      <div>
        <div style="font-weight:700;font-size:14px;color:#C05000;">
          {{ $nbDepasses }} enseignant(s) ont dépassé la charge de {{ $seuil }}h
        </div>
        <div style="font-size:12px;color:#A04000;margin-top:1px;">
          Les heures au-delà de {{ $seuil }}h sont majorées à 150% du taux horaire
        </div>
      </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:8px;">
      @foreach($enseignantsDepasses as $ens)
      @php
        $vol   = (float)($ens->volume_horaire ?? 0);
        $compl = round($vol - $seuil, 1);
        $pct   = round($vol / $seuil * 100);
      @endphp
      <div style="background:#fff;border-radius:8px;padding:10px 14px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div class="avatar av-orange" style="flex-shrink:0;">{{ $ens->initiales }}</div>
        <div style="flex:1;min-width:120px;">
          <div style="font-weight:600;font-size:13px;color:var(--navy);">{{ $ens->nom_complet }}</div>
          <div style="font-size:11px;color:var(--muted);">{{ $ens->grade?->lib_grd }} — {{ $ens->departement?->lib_dep }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:16px;flex-shrink:0;">
          <div style="text-align:center;">
            <div style="font-size:16px;font-weight:700;color:#FF6B35;">{{ number_format($vol,1) }}h</div>
            <div style="font-size:10px;color:var(--muted);">Volume total</div>
          </div>
          <div style="text-align:center;">
            <div style="font-size:16px;font-weight:700;color:#C05000;">+{{ $compl }}h</div>
            <div style="font-size:10px;color:var(--muted);">Complémentaires</div>
          </div>
          <div style="text-align:center;">
            <span style="background:#FF6B35;color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">{{ $pct }}%</span>
            <div style="font-size:10px;color:var(--muted);margin-top:2px;">de la charge</div>
          </div>
        </div>
        {{-- Barre de progression --}}
        <div style="width:100%;margin-top:6px;">
          <div style="height:6px;background:#FFE0C0;border-radius:3px;overflow:hidden;position:relative;">
            {{-- Partie normale (jusqu'à 192h) --}}
            <div style="height:100%;background:#00C07F;border-radius:3px;width:{{ min(round($seuil/$vol*100),100) }}%;display:inline-block;"></div>
            {{-- Partie dépassement --}}
            <div style="height:100%;background:#FF6B35;border-radius:0 3px 3px 0;width:{{ round($compl/$vol*100) }}%;display:inline-block;margin-left:0;"></div>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:9px;color:var(--muted);margin-top:2px;">
            <span style="color:#00C07F;">■ Normal ({{ number_format(min($vol,$seuil),1) }}h)</span>
            <span style="color:#FF6B35;">■ Complémentaire (+{{ $compl }}h)</span>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@else
{{-- Message si personne n'a dépassé --}}
<div style="margin-bottom:24px;">
  <div style="background:#E6FBF3;border:1px solid #00C07F40;border-left:4px solid #00C07F;border-radius:12px;padding:14px 20px;display:flex;align-items:center;gap:10px;">
    <span style="font-size:18px;">✅</span>
    <div style="font-size:13px;color:#009962;font-weight:500;">
      Aucun enseignant n'a dépassé la charge de {{ $seuil }}h pour {{ $annee?->lib_anee ?? 'cette année' }}
    </div>
  </div>
</div>
@endif

<div id="section-repartition" style="scroll-margin-top:80px;"></div>
{{-- ══ RÉPARTITION DES ACTIVITÉS ═══════════════════════════════ --}}
<div style="margin-bottom:24px;">
  <h2 style="font-weight:700;font-size:15px;color:var(--navy);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#00C07F" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    Répartition des activités pédagogiques
    <span style="font-size:12px;font-weight:400;color:var(--muted);">— {{ $annee?->lib_anee ?? 'Toutes années' }}</span>
  </h2>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;">

    {{-- Type d'activité --}}
    <div class="card">
      <div class="card-header"><h3>Par type d'activité</h3></div>
      <div style="padding:20px;">
        @php $totalVol=$repartitionTypes->sum('volume_total')?: 1; $tc=['#00C07F','#FF6B35']; @endphp
        @if($repartitionTypes->isEmpty())
          <p style="color:var(--muted);font-size:13px;text-align:center;padding:16px 0;">Aucune activité</p>
        @else
        <div style="display:flex;align-items:center;gap:20px;">
          <svg width="100" height="100" viewBox="0 0 100 100" style="flex-shrink:0;">
            @php $a=-90; @endphp
            @foreach($repartitionTypes as $ti=>$t)
              @php $p=$t->volume_total/$totalVol;$sw=$p*360;$r=38;$c=2*M_PI*$r;$d=$p*$c;$o=-($a/360)*$c; @endphp
              <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="{{ $tc[$ti%2] }}" stroke-width="16" stroke-dasharray="{{ $d }} {{ $c }}" stroke-dashoffset="{{ $o }}"/>
              @php $a+=$sw; @endphp
            @endforeach
            <circle cx="50" cy="50" r="22" fill="white"/>
            <text x="50" y="47" text-anchor="middle" style="font-size:10px;font-weight:700;fill:#0D1B2A;">{{ number_format($totalVol,0) }}</text>
            <text x="50" y="59" text-anchor="middle" style="font-size:8px;fill:#6B7A8D;">heures</text>
          </svg>
          <div style="flex:1;">
            @foreach($repartitionTypes as $ti=>$t)
            <div style="margin-bottom:10px;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <div style="display:flex;align-items:center;gap:6px;">
                  <span style="width:9px;height:9px;border-radius:50%;background:{{ $tc[$ti%2] }};display:inline-block;flex-shrink:0;"></span>
                  <span style="font-size:12px;color:var(--navy);">{{ $t->lib_typ_act }}</span>
                </div>
                <span style="font-size:12px;font-weight:700;">{{ number_format($t->volume_total,1) }}h</span>
              </div>
              <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden;">
                <div style="height:100%;background:{{ $tc[$ti%2] }};width:{{ round($t->volume_total/$totalVol*100) }}%;"></div>
              </div>
              <div style="font-size:10px;color:var(--muted);margin-top:2px;">{{ $t->nb_activites }} activité(s) — {{ round($t->volume_total/$totalVol*100) }}%</div>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- Niveau de complexité --}}
    <div class="card">
      <div class="card-header"><h3>Par niveau de complexité</h3></div>
      <div style="padding:20px;">
        @php $maxN=$repartitionNiveaux->max('volume_total')?:1; $nc=[1=>'#4A90E2',2=>'#00C07F',3=>'#9B59B6']; $nd=[1=>'Contenus simples + quiz',2=>'Niv.1 + interactifs',3=>'Serious games']; @endphp
        @if($repartitionNiveaux->isEmpty())
          <p style="color:var(--muted);font-size:13px;text-align:center;padding:16px 0;">Aucune activité</p>
        @else
        <div style="display:flex;flex-direction:column;gap:14px;">
          @foreach($repartitionNiveaux as $niv)
          @php $p2=round($niv->volume_total/$maxN*100); @endphp
          <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
              <div style="display:flex;align-items:center;gap:8px;">
                <span style="width:26px;height:26px;border-radius:7px;background:{{ $nc[$niv->niv_comp]??'#ccc' }}20;border:1px solid {{ $nc[$niv->niv_comp]??'#ccc' }}50;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;color:{{ $nc[$niv->niv_comp]??'#ccc' }};flex-shrink:0;">N{{ $niv->niv_comp }}</span>
                <div>
                  <div style="font-size:12.5px;font-weight:500;color:var(--navy);">Niveau {{ $niv->niv_comp }}</div>
                  <div style="font-size:10px;color:var(--muted);">{{ $nd[$niv->niv_comp]??'' }}</div>
                </div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div style="font-size:13px;font-weight:700;">{{ number_format($niv->volume_total,1) }}h</div>
                <div style="font-size:10px;color:var(--muted);">{{ $niv->nb_activites }} acte(s)</div>
              </div>
            </div>
            <div style="height:8px;background:var(--border);border-radius:4px;overflow:hidden;">
              <div style="height:100%;border-radius:4px;background:{{ $nc[$niv->niv_comp]??'#ccc' }};width:{{ $p2 }}%;"></div>
            </div>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    </div>

    {{-- Type de ressource --}}
    <div class="card">
      <div class="card-header"><h3>Par type de ressource</h3></div>
      <div style="padding:20px;">
        @php $maxR=$repartitionRessources->max('volume_total')?:1; $rc=['#00C07F','#4A90E2','#FF6B35','#9B59B6','#1ABC9C','#E74C3C','#F39C12','#3498DB']; @endphp
        @if($repartitionRessources->isEmpty())
          <p style="color:var(--muted);font-size:13px;text-align:center;padding:16px 0;">Aucune activité</p>
        @else
        <div style="display:flex;flex-direction:column;gap:8px;">
          @foreach($repartitionRessources as $ri=>$r)
          @php $pr=round($r->volume_total/$maxR*100); @endphp
          <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:110px;font-size:11px;color:var(--muted);text-align:right;flex-shrink:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $r->lib_typ_ress }}">{{ $r->lib_typ_ress }}</div>
            <div style="flex:1;height:16px;background:var(--border);border-radius:4px;overflow:hidden;">
              <div style="height:100%;background:{{ $rc[$ri%count($rc)] }};border-radius:4px;width:{{ $pr }}%;"></div>
            </div>
            <div style="width:38px;font-size:11px;font-weight:700;text-align:right;flex-shrink:0;">{{ number_format($r->volume_total,1) }}h</div>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    </div>

  </div>
</div>

<div id="section-departements" style="scroll-margin-top:80px;"></div>
{{-- ══ VOLUME PAR DÉPARTEMENT ═══════════════════════════════════ --}}
<div style="margin-bottom:24px;">
  <h2 style="font-weight:700;font-size:15px;color:var(--navy);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#00C07F" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Volume horaire par département
    <span style="font-size:12px;font-weight:400;color:var(--muted);">— {{ $annee?->lib_anee ?? 'Toutes années' }}</span>
  </h2>
  <div class="card">
    <div class="card-header">
      <h3>Répartition par département</h3>
      <span style="font-size:12px;color:var(--muted);">{{ $volumeParDepartement->count() }} département(s)</span>
    </div>
    @php $maxDep=$volumeParDepartement->max('volume_total')?:1; $totDep=$volumeParDepartement->sum('volume_total')?:1; $dc=['#00C07F','#4A90E2','#FF6B35','#9B59B6','#1ABC9C','#E74C3C']; @endphp
    @if($volumeParDepartement->isEmpty())
      <div style="padding:32px;text-align:center;color:var(--muted);font-size:13px;">Aucune donnée</div>
    @else
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
      @foreach($volumeParDepartement as $di=>$dep)
      @php $pd=$maxDep>0?round($dep->volume_total/$maxDep*100):0; @endphp
      <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;gap:12px;">
          <div style="display:flex;align-items:center;gap:8px;min-width:0;flex:1;">
            <span style="width:10px;height:10px;border-radius:50%;background:{{ $dc[$di%6] }};flex-shrink:0;display:inline-block;"></span>
            <span style="font-size:13px;font-weight:500;color:var(--navy);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $dep->lib_dep }}">{{ $dep->lib_dep }}</span>
          </div>
          <div style="display:flex;align-items:center;gap:20px;flex-shrink:0;">
            <span style="font-size:11px;color:var(--muted);white-space:nowrap;">{{ $dep->nb_enseignants }} ens.</span>
            <span style="font-size:11px;color:var(--muted);white-space:nowrap;">{{ $dep->nb_activites }} act.</span>
            <span style="font-size:15px;font-weight:700;color:var(--navy);white-space:nowrap;">{{ number_format($dep->volume_total,1) }}h</span>
          </div>
        </div>
        <div style="height:10px;background:var(--border);border-radius:6px;overflow:hidden;">
          <div style="height:100%;border-radius:6px;background:{{ $dc[$di%6] }};width:{{ $pd }}%;"></div>
        </div>
        <div style="font-size:10px;color:var(--muted);margin-top:3px;text-align:right;">{{ round($dep->volume_total/$totDep*100,1) }}% du volume total</div>
      </div>
      @endforeach
    </div>
    <div style="overflow-x:auto;border-top:1px solid var(--border);">
      <table style="width:100%;border-collapse:collapse;min-width:500px;">
        <thead><tr style="background:#FAFBFC;">
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Département</th>
          <th style="padding:10px 16px;text-align:center;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Enseignants</th>
          <th style="padding:10px 16px;text-align:center;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Activités</th>
          <th style="padding:10px 16px;text-align:right;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Volume (h)</th>
          <th style="padding:10px 16px;text-align:right;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Part (%)</th>
        </tr></thead>
        <tbody>
          @foreach($volumeParDepartement as $di=>$dep)
          <tr style="border-top:1px solid #F0F2F5;">
            <td style="padding:11px 16px;"><div style="display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:{{ $dc[$di%6] }};flex-shrink:0;display:inline-block;"></span><span style="font-size:13px;">{{ $dep->lib_dep }}</span></div></td>
            <td style="padding:11px 16px;text-align:center;font-size:13px;color:var(--muted);">{{ $dep->nb_enseignants }}</td>
            <td style="padding:11px 16px;text-align:center;font-size:13px;color:var(--muted);">{{ $dep->nb_activites }}</td>
            <td style="padding:11px 16px;text-align:right;font-weight:700;font-size:14px;color:{{ $dc[$di%6] }};">{{ number_format($dep->volume_total,1) }}h</td>
            <td style="padding:11px 16px;text-align:right;font-size:12px;color:var(--muted);">{{ round($dep->volume_total/$totDep*100,1) }}%</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot><tr style="background:#E6FBF3;border-top:2px solid var(--border);">
          <td style="padding:11px 16px;font-weight:700;font-size:13px;">TOTAL</td>
          <td style="padding:11px 16px;text-align:center;font-weight:700;">{{ $volumeParDepartement->sum('nb_enseignants') }}</td>
          <td style="padding:11px 16px;text-align:center;font-weight:700;">{{ $volumeParDepartement->sum('nb_activites') }}</td>
          <td style="padding:11px 16px;text-align:right;font-weight:700;font-size:15px;color:#009962;">{{ number_format($volumeParDepartement->sum('volume_total'),1) }}h</td>
          <td style="padding:11px 16px;text-align:right;font-weight:700;">100%</td>
        </tr></tfoot>
      </table>
    </div>
    @endif
  </div>
</div>


<div id="section-mensuelles" style="scroll-margin-top:80px;"></div>
{{-- ══ STATISTIQUES MENSUELLES ════════════════════════════════ --}}
<div style="margin-bottom:24px;">
  <h2 style="font-weight:700;font-size:15px;color:var(--navy);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#00C07F" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
    Statistiques mensuelles
    <span style="font-size:12px;font-weight:400;color:var(--muted);">— {{ $annee?->lib_anee ?? 'Toutes années' }}</span>
  </h2>

  @php $moisNoms = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc']; @endphp

  <div class="card">
    <div class="card-header">
      <h3>Évolution mensuelle des volumes horaires</h3>
      @php
        $totalAnnee   = $statsMensuelles->sum('volume');
        $moisActif    = $statsMensuelles->filter(fn($m) => $m['volume'] > 0)->count();
        $moyenneMois  = $moisActif > 0 ? round($totalAnnee / $moisActif, 1) : 0;
        $maxMois      = $statsMensuelles->max('volume') ?: 1;
        $moisPic      = $statsMensuelles->sortByDesc('volume')->first();
      @endphp
      <span style="font-size:12px;color:var(--muted);">Total : <strong style="color:var(--navy);">{{ number_format($totalAnnee,1) }}h</strong></span>
    </div>

    {{-- Mini KPI mensuels ─────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0;border-bottom:1px solid var(--border);">
      <div style="padding:14px 20px;border-right:1px solid var(--border);">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Total annuel</div>
        <div style="font-size:22px;font-weight:700;color:var(--navy);">{{ number_format($totalAnnee,1) }}<span style="font-size:13px;font-weight:400;color:var(--muted);margin-left:3px;">h</span></div>
      </div>
      <div style="padding:14px 20px;border-right:1px solid var(--border);">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Mois actifs</div>
        <div style="font-size:22px;font-weight:700;color:var(--navy);">{{ $moisActif }}<span style="font-size:13px;font-weight:400;color:var(--muted);margin-left:3px;">/ 12</span></div>
      </div>
      <div style="padding:14px 20px;border-right:1px solid var(--border);">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Moy. par mois</div>
        <div style="font-size:22px;font-weight:700;color:var(--green);">{{ $moyenneMois }}<span style="font-size:13px;font-weight:400;color:var(--muted);margin-left:3px;">h</span></div>
      </div>
      <div style="padding:14px 20px;">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Mois pic</div>
        <div style="font-size:16px;font-weight:700;color:#FF6B35;">
          {{ $moisPic && $moisPic['volume'] > 0 ? $moisNoms[$moisPic['mois']-1] : '—' }}
          @if($moisPic && $moisPic['volume'] > 0)
          <span style="font-size:12px;color:var(--muted);">({{ number_format($moisPic['volume'],1) }}h)</span>
          @endif
        </div>
      </div>
    </div>

    {{-- Graphique barres mensuel ─────────────────────────── --}}
    <div style="padding:24px 20px;">
      @php $moisCourant = (int)date('n'); @endphp

      {{-- Barres SVG ──────────────────────────────────────── --}}
      <div style="display:flex;align-items:flex-end;gap:6px;height:140px;padding-bottom:28px;position:relative;border-bottom:2px solid var(--border);">

        {{-- Lignes de grille --}}
        @foreach([25,50,75,100] as $pctGrid)
        <div style="position:absolute;left:0;right:0;bottom:{{ 28 + $pctGrid * 112/100 }}px;border-top:1px dashed #E8ECF0;z-index:0;"></div>
        @endforeach

        @foreach($statsMensuelles as $m)
        @php
          $h         = $maxMois > 0 ? round($m['volume'] / $maxMois * 112) : 0;
          $isActuel  = $m['mois'] === $moisCourant;
          $hasData   = $m['volume'] > 0;
          $color     = $isActuel ? '#0D1B2A' : ($hasData ? '#00C07F' : '#E2E8F0');
        @endphp
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;position:relative;z-index:1;">
          {{-- Valeur au-dessus de la barre --}}
          @if($hasData)
          <div style="font-size:9px;font-weight:700;color:{{ $isActuel?'#0D1B2A':'#00C07F' }};white-space:nowrap;position:absolute;bottom:{{ 28 + $h + 3 }}px;">
            {{ number_format($m['volume'],0) }}h
          </div>
          @endif
          {{-- Barre --}}
          <div style="width:100%;position:absolute;bottom:28px;height:{{ max($h,2) }}px;background:{{ $color }};border-radius:4px 4px 0 0;transition:height .4s ease;"></div>
          {{-- Label mois --}}
          <div style="position:absolute;bottom:8px;font-size:10px;font-weight:{{ $isActuel?'700':'400' }};color:{{ $isActuel?'#0D1B2A':'var(--muted)' }};">
            {{ $moisNoms[$m['mois']-1] }}
          </div>
        </div>
        @endforeach
      </div>

      {{-- Légende ─────────────────────────────────────────── --}}
      <div style="display:flex;align-items:center;gap:20px;margin-top:12px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--muted);">
          <span style="width:12px;height:12px;background:#00C07F;border-radius:3px;display:inline-block;"></span> Mois avec activités
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--muted);">
          <span style="width:12px;height:12px;background:#0D1B2A;border-radius:3px;display:inline-block;"></span> Mois en cours
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--muted);">
          <span style="width:12px;height:12px;background:#E2E8F0;border-radius:3px;display:inline-block;"></span> Aucune activité
        </div>
      </div>
    </div>

    {{-- Tableau mensuel détaillé ─────────────────────────── --}}
    <div style="overflow-x:auto;border-top:1px solid var(--border);">
      <table style="width:100%;border-collapse:collapse;min-width:600px;">
        <thead>
          <tr style="background:#FAFBFC;">
            <th style="padding:9px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Mois</th>
            <th style="padding:9px 16px;text-align:center;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Activités</th>
            <th style="padding:9px 16px;text-align:right;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Volume (h)</th>
            <th style="padding:9px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Progression</th>
            <th style="padding:9px 16px;text-align:right;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Part (%)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($statsMensuelles as $m)
          @php
            $isNow  = $m['mois'] === $moisCourant;
            $pctBar = $maxMois > 0 ? round($m['volume'] / $maxMois * 100) : 0;
            $pctTot = $totalAnnee > 0 ? round($m['volume'] / $totalAnnee * 100, 1) : 0;
          @endphp
          <tr style="border-top:1px solid #F0F2F5;{{ $isNow ? 'background:#F0FBF8;' : '' }}">
            <td style="padding:9px 16px;">
              <div style="display:flex;align-items:center;gap:8px;">
                @if($isNow)
                <span style="background:#0D1B2A;color:#00C07F;font-size:9px;font-weight:700;padding:2px 7px;border-radius:10px;">EN COURS</span>
                @endif
                <span style="font-size:13px;font-weight:{{ $isNow?'600':'400' }};color:var(--navy);">{{ $moisNoms[$m['mois']-1] }}</span>
              </div>
            </td>
            <td style="padding:9px 16px;text-align:center;font-size:13px;color:{{ $m['nb_activites']>0?'var(--navy)':'var(--muted)' }};">
              {{ $m['nb_activites'] > 0 ? $m['nb_activites'] : '—' }}
            </td>
            <td style="padding:9px 16px;text-align:right;font-weight:700;font-size:14px;color:{{ $m['volume']>0?'#009962':'var(--muted)' }};">
              {{ $m['volume'] > 0 ? number_format($m['volume'],1).'h' : '—' }}
            </td>
            <td style="padding:9px 16px;">
              @if($m['volume'] > 0)
              <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden;max-width:200px;">
                <div style="height:100%;background:{{ $isNow?'#0D1B2A':'#00C07F' }};border-radius:3px;width:{{ $pctBar }}%;"></div>
              </div>
              @else
              <span style="font-size:12px;color:var(--muted);">—</span>
              @endif
            </td>
            <td style="padding:9px 16px;text-align:right;font-size:12px;color:{{ $m['volume']>0?'var(--muted)':'#E2E8F0' }};">
              {{ $m['volume'] > 0 ? $pctTot.'%' : '—' }}
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background:#E6FBF3;border-top:2px solid var(--border);">
            <td style="padding:9px 16px;font-weight:700;font-size:13px;">TOTAL</td>
            <td style="padding:9px 16px;text-align:center;font-weight:700;">{{ $statsMensuelles->sum('nb') }}</td>
            <td style="padding:9px 16px;text-align:right;font-weight:700;font-size:15px;color:#009962;">{{ number_format($totalAnnee,1) }}h</td>
            <td style="padding:9px 16px;"></td>
            <td style="padding:9px 16px;text-align:right;font-weight:700;">100%</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>


{{-- ══ STATISTIQUES MENSUELLES ══════════════════════════════════ --}}
<div style="margin-bottom:24px;">
  <h2 style="font-weight:700;font-size:15px;color:var(--navy);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#00C07F" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
    Statistiques mensuelles
    <span style="font-size:12px;font-weight:400;color:var(--muted);">— {{ $annee?->lib_anee ?? 'Toutes années' }}</span>
  </h2>

  <div class="card">
    <div class="card-header">
      <h3>Évolution mensuelle des volumes horaires</h3>
      @php
        $totalMensuel  = $statsMensuelles->sum('volume');
        $moisActif     = $statsMensuelles->filter(fn($m) => $m['volume'] > 0)->count();
        $moisPic       = $statsMensuelles->sortByDesc('volume')->first();
      @endphp
      <span style="font-size:12px;color:var(--muted);">Total : <strong style="color:var(--green-dark);">{{ number_format($totalMensuel,1) }}h</strong></span>
    </div>

    {{-- KPI mini --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);border-bottom:1px solid var(--border);">
      <div style="padding:14px 20px;border-right:1px solid var(--border);text-align:center;">
        <div style="font-weight:700;font-size:20px;color:var(--navy);">{{ number_format($totalMensuel,1) }}h</div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px;">Volume annuel</div>
      </div>
      <div style="padding:14px 20px;border-right:1px solid var(--border);text-align:center;">
        <div style="font-weight:700;font-size:20px;color:var(--navy);">{{ $moisActif }}</div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px;">Mois avec activité</div>
      </div>
      <div style="padding:14px 20px;text-align:center;">
        @if($moisPic && $moisPic['volume'] > 0)
        <div style="font-weight:700;font-size:20px;color:var(--green-dark);">{{ $moisNoms[$moisPic['mois']-1] }}</div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px;">Mois le plus chargé ({{ number_format($moisPic['volume'],1) }}h)</div>
        @else
        <div style="font-weight:700;font-size:20px;color:var(--muted);">—</div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px;">Aucun pic</div>
        @endif
      </div>
    </div>

    {{-- Graphique barres vertical --}}
    <div style="padding:24px 20px;">
      @php
        $maxVol = $statsMensuelles->max('volume') ?: 1;
        $chartH = 140; // hauteur max en px
      @endphp

      @if($totalMensuel == 0)
        <div style="text-align:center;padding:32px;color:var(--muted);font-size:13px;">
          Aucune activité enregistrée pour cette année
        </div>
      @else

      {{-- Barres SVG ──────────────────────────────────────────── --}}
      <div style="display:flex;align-items:flex-end;gap:6px;height:{{ $chartH + 40 }}px;border-bottom:2px solid var(--border);border-left:1px solid var(--border);padding:0 4px 0 8px;position:relative;overflow:visible;">

        {{-- Lignes de guide horizontales --}}
        @foreach([25,50,75,100] as $pct3)
        <div style="position:absolute;left:0;right:0;bottom:{{ round($pct3/100*$chartH) + 2 }}px;height:1px;background:rgba(0,0,0,.05);z-index:0;"></div>
        @endforeach

        @foreach($statsMensuelles as $ms)
        @php
          $h     = $maxVol > 0 ? round($ms['volume'] / $maxVol * $chartH) : 0;
          $isNow = $ms['mois'] == now()->month;
          $col   = $ms['volume'] > 0 ? ($isNow ? '#009962' : '#00C07F') : '#E2E8F0';
        @endphp
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;gap:4px;position:relative;z-index:1;">
          {{-- Valeur au-dessus --}}
          @if($ms['volume'] > 0)
          <div style="font-size:9px;font-weight:700;color:{{ $col }};white-space:nowrap;position:absolute;bottom:{{ $h + 6 }}px;">{{ number_format($ms['volume'],0) }}h</div>
          @endif
          {{-- Barre --}}
          <div style="width:100%;height:{{ max($h,2) }}px;background:{{ $col }};border-radius:4px 4px 0 0;transition:opacity .2s;min-height:2px;"
               title="{{ $moisNoms[$ms['mois']-1] }} : {{ $ms['volume'] }}h ({{ $ms['nb_activites'] }} activité(s))">
          </div>
          {{-- Label mois --}}
          <div style="font-size:10px;color:{{ $isNow?'var(--green-dark)':'var(--muted)' }};font-weight:{{ $isNow?'700':'400' }};margin-top:4px;white-space:nowrap;">
            {{ $moisNoms[$ms['mois']-1] }}
          </div>
        </div>
        @endforeach
      </div>

      {{-- Légende --}}
      <div style="display:flex;gap:20px;justify-content:center;margin-top:14px;font-size:11px;color:var(--muted);">
        <div style="display:flex;align-items:center;gap:5px;"><span style="width:12px;height:12px;background:#00C07F;border-radius:3px;display:inline-block;"></span> Mois avec activités</div>
        <div style="display:flex;align-items:center;gap:5px;"><span style="width:12px;height:12px;background:#009962;border-radius:3px;display:inline-block;"></span> Mois actuel</div>
        <div style="display:flex;align-items:center;gap:5px;"><span style="width:12px;height:12px;background:#E2E8F0;border-radius:3px;display:inline-block;"></span> Aucune activité</div>
      </div>

      @endif
    </div>

    {{-- Tableau mensuel --}}
    <div style="overflow-x:auto;border-top:1px solid var(--border);">
      <table style="width:100%;border-collapse:collapse;min-width:600px;">
        <thead>
          <tr style="background:#FAFBFC;">
            <th style="padding:8px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Mois</th>
            @foreach($statsMensuelles as $ms)
            <th style="padding:8px 6px;text-align:center;font-size:10px;font-weight:600;color:{{ $ms['mois']==now()->month?'var(--green-dark)':'var(--muted)' }};text-transform:uppercase;">
              {{ $moisNoms[$ms['mois']-1] }}
            </th>
            @endforeach
            <th style="padding:8px 12px;text-align:right;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Total</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-top:1px solid #F0F2F5;">
            <td style="padding:10px 12px;font-size:12px;font-weight:500;color:var(--navy);">Volume (h)</td>
            @foreach($statsMensuelles as $ms)
            <td style="padding:10px 6px;text-align:center;font-size:12px;font-weight:{{ $ms['volume']>0?'700':'400' }};color:{{ $ms['volume']>0?'var(--green-dark)':'var(--muted)' }};">
              {{ $ms['volume'] > 0 ? number_format($ms['volume'],1) : '—' }}
            </td>
            @endforeach
            <td style="padding:10px 12px;text-align:right;font-weight:700;font-size:13px;color:var(--green-dark);">{{ number_format($totalMensuel,1) }}h</td>
          </tr>
          <tr style="border-top:1px solid #F0F2F5;">
            <td style="padding:10px 12px;font-size:12px;font-weight:500;color:var(--navy);">Activités</td>
            @foreach($statsMensuelles as $ms)
            <td style="padding:10px 6px;text-align:center;font-size:12px;color:{{ $ms['nb_activites']>0?'var(--navy)':'var(--muted)' }};">
              {{ $ms['nb_activites'] > 0 ? $ms['nb_activites'] : '—' }}
            </td>
            @endforeach
            <td style="padding:10px 12px;text-align:right;font-weight:700;font-size:13px;color:var(--navy);">{{ $statsMensuelles->sum('nb_activites') }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div id="section-enseignants" style="scroll-margin-top:80px;"></div>
{{-- ══ TABLE ENSEIGNANTS ════════════════════════════════════════ --}}
<div>
  <h2 style="font-weight:700;font-size:15px;color:var(--navy);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#00C07F" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    Enseignants
    <span style="font-size:12px;font-weight:400;color:var(--muted);">— {{ $annee?->lib_anee ?? 'Aucune année active' }}</span>
  </h2>
  <div class="card">
    <div class="card-header">
      <h3>{{ $enseignants->total() }} enseignant(s)</h3>
      <a href="{{ route('secretaire.enseignants.create') }}" class="btn btn-green btn-sm">+ Ajouter</a>
    </div>
    <div style="padding:10px 16px;border-bottom:1px solid var(--border);display:flex;gap:10px;flex-wrap:wrap;">
      <div style="position:relative;flex:1;min-width:160px;">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--muted);pointer-events:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" id="srch" oninput="ft()" placeholder="Rechercher…" style="width:100%;padding:7px 10px 7px 32px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#FAFAFA;outline:none;font-family:inherit;color:var(--navy);" onfocus="this.style.borderColor='#00C07F'" onblur="this.style.borderColor='var(--border)'">
      </div>
      <select id="fst" onchange="ft()" style="padding:7px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#FAFAFA;outline:none;cursor:pointer;font-family:inherit;color:var(--navy);">
        <option value="">Tous statuts</option>
        <option value="permanent">Permanent</option>
        <option value="vacataire">Vacataire</option>
      </select>
    </div>
    <div style="overflow-x:auto;">
      <table id="et" style="width:100%;border-collapse:collapse;min-width:600px;">
        <thead><tr style="background:#FAFBFC;">
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Enseignant</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Grade</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;" class="hide-mobile">Statut</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;" class="hide-mobile">Département</th>
          <th style="padding:10px 16px;text-align:right;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Volume (h)</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Actions</th>
        </tr></thead>
        <tbody>
          @php $avc=['av-green','av-blue','av-purple','av-orange','av-teal']; @endphp
          @forelse($enseignants as $ens)
          @php $vol2=(float)($ens->volume_horaire??0); $depasse=$vol2>$seuil; @endphp
          <tr data-statut="{{ strtolower($ens->statut?->lib_stat??'') }}" data-nom="{{ strtolower($ens->nom_complet) }}" style="border-top:1px solid #F0F2F5;{{ $depasse?'background:#FFF8F0;':'' }}">
            <td style="padding:11px 16px;">
              <div style="display:flex;align-items:center;gap:10px;">
                <div class="avatar {{ $depasse?'av-orange':$avc[$loop->index%5] }}">{{ $ens->initiales }}</div>
                <div>
                  <div style="font-weight:500;font-size:13.5px;">{{ $ens->nom_complet }}</div>
                  <div style="font-size:11px;color:var(--muted);">{{ $ens->utilisateur?->email??'—' }}</div>
                </div>
              </div>
            </td>
            <td style="padding:11px 16px;"><span class="badge-green">{{ $ens->grade?->lib_grd??'—' }}</span></td>
            <td style="padding:11px 16px;" class="hide-mobile">
              @if(strtolower($ens->statut?->lib_stat??'') === 'permanent')
                <span class="badge-blue">Permanent</span>
              @else
                <span class="badge-orange">Vacataire</span>
              @endif
            </td>
            <td style="padding:11px 16px;font-size:13px;color:var(--muted);" class="hide-mobile">{{ $ens->departement?->lib_dep??'—' }}</td>
            <td style="padding:11px 16px;text-align:right;">
              <span style="font-size:14px;font-weight:700;color:{{ $depasse?'#FF6B35':'var(--navy)' }};">{{ number_format($vol2,1) }}h</span>
              @if($depasse)
              <div style="font-size:10px;color:#FF6B35;margin-top:1px;">+{{ round($vol2-$seuil,1) }}h ⚠️</div>
              @endif
            </td>
            <td style="padding:11px 16px;">
              <div style="display:flex;gap:6px;">
                <a href="{{ route('secretaire.enseignants.edit',$ens) }}" class="btn btn-outline btn-sm">Modifier</a>
                <form method="POST" action="{{ route('secretaire.enseignants.destroy',$ens) }}" onsubmit="return confirm('Supprimer ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Suppr.</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="padding:40px;text-align:center;color:var(--muted);">Aucun enseignant</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($enseignants->hasPages())
    <div style="padding:14px 16px;border-top:1px solid var(--border);">{{ $enseignants->links() }}</div>
    @endif
  </div>
</div>


@push('scripts')
<script>
function ft(){
  var s=document.getElementById('srch').value.toLowerCase();
  var st=document.getElementById('fst').value.toLowerCase();
  document.querySelectorAll('#et tbody tr[data-nom]').forEach(function(r){
    r.style.display=(!s||r.dataset.nom.includes(s))&&(!st||r.dataset.statut.includes(st))?'':'none';
  });
}
</script>
@endpush
@endsection
