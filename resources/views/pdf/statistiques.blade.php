<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Rapport Statistiques — {{ $annee->lib_anee }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI','Roboto',Arial,sans-serif;font-size:11px;color:#1e293b;background:#fff;padding:28px}
@media print{
  body{padding:0;margin:0}
  .no-print{display:none!important}
  .page-break{page-break-before:always}
  @page{size:A4 portrait;margin:12mm 10mm}
}

/* Header professionnel */
.header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;padding-bottom:12px;border-bottom:1px solid #e2e8f0;}
.logo-zone{display:flex;align-items:center;gap:14px;}
.logo-mark{width:48px;height:48px;background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;color:#fff;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,0.08);}
.org-name{font-weight:700;font-size:14px;color:#0f172a;letter-spacing:-0.2px;}
.org-sub{font-size:9px;color:#64748b;margin-top:2px;}
.org-contact{font-size:9px;color:#94a3b8;margin-top:1px;}
.meta-right{text-align:right;}
.meta-title{font-weight:700;font-size:18px;color:#0f172a;text-transform:uppercase;letter-spacing:1px;}
.meta-sub{font-size:10px;color:#64748b;margin-top:3px;}

/* Section titre */
.sec-title{font-weight:600;font-size:13px;color:#0f172a;text-transform:uppercase;letter-spacing:0.5px;padding:8px 0;margin:20px 0 12px 0;border-bottom:2px solid #e2e8f0;}

/* Tables */
table{width:100%;border-collapse:collapse;margin-bottom:14px;font-size:10px;}
thead tr{background:#f1f5f9;}
th{padding:8px 10px;color:#1e293b;font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;text-align:left;border-bottom:1px solid #e2e8f0;}
th.r,td.r{text-align:right;}
th.c,td.c{text-align:center;}
td{padding:8px 10px;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
tbody tr:hover{background:#f8fafc;}
tfoot tr{background:#f8fafc;border-top:1px solid #e2e8f0;}
tfoot td{font-weight:600;font-size:10px;}

/* Badges */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:9px;font-weight:500;}
.badge-blue{background:#eef2ff;color:#1e40af;}
.badge-green{background:#ecfdf5;color:#065f46;}
.badge-orange{background:#fff7ed;color:#9a3412;}
.badge-purple{background:#f3e8ff;color:#6b21a5;}
.badge-gray{background:#f1f5f9;color:#475569;}

/* Barres de progression */
.bar-wrap{display:flex;align-items:center;gap:10px;}
.bar-bg{flex:1;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden;}
.bar-fill{height:100%;border-radius:3px;}
.bar-pct{width:40px;text-align:right;font-size:9px;font-weight:500;color:#475569;}

/* Graphique mensuel */
.chart-wrap{display:flex;align-items:flex-end;gap:6px;height:90px;border-bottom:1px solid #e2e8f0;margin-bottom:8px;padding:0 4px;}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;}
.bar-rect{width:100%;border-radius:4px 4px 0 0;min-height:2px;transition:all 0.2s;}
.bar-lbl{font-size:8px;color:#64748b;margin-top:5px;font-weight:500;}

/* KPI résumé */
.kpi-row{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:24px;}
.kpi-box{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 12px;text-align:center;box-shadow:0 1px 2px rgba(0,0,0,0.03);}
.kpi-val{font-weight:700;font-size:22px;color:#0f172a;line-height:1.2;}
.kpi-val.small{font-size:16px;}
.kpi-val.green{color:#065f46;}
.kpi-val.blue{color:#1e40af;}
.kpi-val.orange{color:#9a3412;}
.kpi-lbl{font-size:9px;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;margin-top:6px;font-weight:500;}

/* Signature */
.sig-section{margin-top:32px;padding-top:20px;border-top:1px solid #e2e8f0;}
.sig-title{font-size:10px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:1px;margin-bottom:16px;}
.sig-row{display:flex;gap:24px;}
.sig-box{flex:1;text-align:center;}
.sig-role{font-size:10px;font-weight:600;color:#0f172a;margin-bottom:4px;}
.sig-who{font-size:9px;color:#64748b;margin-bottom:28px;}
.sig-line{border-bottom:1px solid #0f172a;margin:0 12px 5px;}
.sig-date{font-size:8px;color:#94a3b8;margin-top:4px;}

/* Footer */
.doc-footer{display:flex;justify-content:space-between;align-items:center;margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:8px;color:#94a3b8;}

/* Boutons impression */
.print-btn{position:fixed;bottom:24px;right:24px;display:flex;align-items:center;gap:8px;background:#0f172a;color:#fff;border:none;padding:10px 20px;border-radius:40px;font-size:12px;font-weight:500;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,0.15);font-family:inherit;z-index:999;}
.excel-btn{position:fixed;bottom:24px;right:180px;display:flex;align-items:center;gap:8px;background:#065f46;color:#fff;border:none;padding:10px 20px;border-radius:40px;font-size:12px;font-weight:500;cursor:pointer;text-decoration:none;box-shadow:0 4px 12px rgba(0,0,0,0.12);font-family:inherit;z-index:999;}
</style>
</head>
<body>

{{-- EN-TÊTE --}}
<div class="header">
  <div class="logo-zone">
    <div class="logo-mark">UV</div>
    <div>
      <div class="org-name">UNIVERSITÉ VIRTUELLE DE CÔTE D'IVOIRE</div>
      <div class="org-sub">Direction des Études et de la Scolarité</div>
      <div class="org-contact">secretariat@uvci.edu.ci · uvci.edu.ci</div>
    </div>
  </div>
  <div class="meta-right">
    <div class="meta-title">RAPPORT STATISTIQUE</div>
    <div class="meta-sub">Année académique {{ $annee->lib_anee }}</div>
    <div class="meta-sub">Émis le {{ $date }}</div>
  </div>
</div>

{{-- KPI --}}
@php
  $totalActivites = $stats['types']->sum('nb_activites');
  $totalVolume    = $stats['types']->sum('volume_total');
  $nbDepasses     = $stats['depasses']->count();
  $moisPic        = $stats['mensuelles']->sortByDesc('volume')->first();
@endphp
<div class="kpi-row">
  <div class="kpi-box"><div class="kpi-val">{{ $totalActivites }}</div><div class="kpi-lbl">Activités enregistrées</div></div>
  <div class="kpi-box"><div class="kpi-val green">{{ number_format($totalVolume,1) }}<span style="font-size:12px;">h</span></div><div class="kpi-lbl">Volume horaire total</div></div>
  <div class="kpi-box"><div class="kpi-val blue">{{ $stats['departements']->count() }}</div><div class="kpi-lbl">Départements</div></div>
  <div class="kpi-box"><div class="kpi-val orange">{{ $nbDepasses }}</div><div class="kpi-lbl">Dépassements seuil</div></div>
  <div class="kpi-box"><div class="kpi-val small">{{ $moisPic && $moisPic['volume']>0 ? $moisPic['nom'] : '—' }}</div><div class="kpi-lbl">Mois le plus actif</div></div>
</div>

{{-- 1. RÉPARTITION PAR TYPE D'ACTIVITÉ --}}
<div class="sec-title">📊 1. Répartition par type d'activité</div>
@php $totT = $stats['types']->sum('volume_total') ?: 1; $tColors=['#1e40af','#9a3412']; @endphp
<table>
  <thead><tr><th>Type d'activité</th><th class="c">Effectif</th><th class="r">Volume (h)</th><th style="width:200px;">Distribution</th><th class="r">Part</th></tr></thead>
  <tbody>
    @foreach($stats['types'] as $ti => $t)
    <tr>
      <td><span class="badge {{ $ti===0?'badge-blue':'badge-orange' }}">{{ $t->lib_typ_act }}</span></td>
      <td class="c">{{ $t->nb_activites }} <span style="color:#94a3b8;font-size:9px;">act.</span></div></td>
      <td class="r" style="font-weight:600;">{{ number_format($t->volume_total,1) }} <span style="color:#94a3b8;font-size:9px;">h</span></div></td>
      <td><div class="bar-wrap"><div class="bar-bg"><div class="bar-fill" style="background:{{ $tColors[$ti%2] }};width:{{ round($t->volume_total/$totT*100) }}%"></div></div><span class="bar-pct">{{ round($t->volume_total/$totT*100) }}%</span></div></div></td>
    </tr>
    @endforeach
  </tbody>
  <tfoot><td><td colspan="2">TOTAL GÉNÉRAL</div><td class="r" style="font-weight:600;">{{ number_format($totalVolume,1) }} h</div><td></td><td class="r">100%</div></td></tfoot>
</table>

{{-- 2. RÉPARTITION PAR NIVEAU DE COMPLEXITÉ --}}
<div class="sec-title">🎯 2. Répartition par niveau de complexité</div>
@php
  $totN = $stats['niveaux']->sum('volume_total') ?: 1;
  $nivColors=[1=>'#1e40af',2=>'#065f46',3=>'#6b21a5'];
  $nivDesc=[1=>'Contenus simples + quiz',2=>'Niv.1 + activités interactives',3=>'Serious games / simulations'];
@endphp
<tr>
  <thead><tr><th>Niveau</th><th>Description</th><th class="c">Act.</th><th class="r">Volume (h)</th><th style="width:200px;">Distribution</th><th class="r">Part</th></tr></thead>
  <tbody>
    @foreach($stats['niveaux'] as $niv)
    <tr>
      <td><span style="display:inline-flex;width:28px;height:28px;border-radius:8px;background:{{ $nivColors[$niv->niv_comp]??'#64748b' }}10;align-items:center;justify-content:center;font-weight:700;font-size:11px;color:{{ $nivColors[$niv->niv_comp]??'#64748b' }};">N{{ $niv->niv_comp }}</span></div>
      <td style="color:#475569;">{{ $nivDesc[$niv->niv_comp]??'—' }}</div>
      <td class="c">{{ $niv->nb_activites }}</div>
      <td class="r" style="font-weight:600;">{{ number_format($niv->volume_total,1) }} h</div>
      <td><div class="bar-wrap"><div class="bar-bg"><div class="bar-fill" style="background:{{ $nivColors[$niv->niv_comp]??'#64748b' }};width:{{ round($niv->volume_total/$totN*100) }}%"></div></div><span class="bar-pct">{{ round($niv->volume_total/$totN*100) }}%</span></div></div>
      <td class="r">{{ round($niv->volume_total/$totN*100,1) }}%</div>
    </tr>
    @endforeach
  </tbody>
  <tfoot><td><td colspan="2">TOTAL</div><td class="c">{{ $stats['niveaux']->sum('nb_activites') }}</div><td class="r">{{ number_format($stats['niveaux']->sum('volume_total'),1) }} h</div><td></td><td class="r">100%</div></tr></tfoot>
</table>

{{-- 3. VOLUME PAR DÉPARTEMENT --}}
<div class="sec-title">🏛️ 3. Volume horaire par département</div>
@php $totD = $stats['departements']->sum('volume_total') ?: 1; $dColors=['#1e40af','#065f46','#9a3412','#6b21a5','#0d9488','#dc2626']; @endphp
</table>
  <thead><tr><th>Département</th><th class="c">Enseignants</th><th class="c">Activités</th><th class="r">Volume (h)</th><th style="width:180px;">Distribution</th><th class="r">Part</th></tr></thead>
  <tbody>
    @foreach($stats['departements'] as $di => $dep)
    <tr>
      <td style="font-weight:500;">{{ $dep->lib_dep }}</div>
      <td class="c">{{ $dep->nb_enseignants }}</div>
      <td class="c">{{ $dep->nb_activites }}</div>
      <td class="r" style="font-weight:600;color:{{ $dColors[$di%6] }};">{{ number_format($dep->volume_total,1) }} h</div>
      <td><div class="bar-wrap"><div class="bar-bg"><div class="bar-fill" style="background:{{ $dColors[$di%6] }};width:{{ round($dep->volume_total/$totD*100) }}%"></div></div><span class="bar-pct">{{ round($dep->volume_total/$totD*100) }}%</span></div></div>
      <td class="r">{{ round($dep->volume_total/$totD*100,1) }}%</div>
    </tr>
    @endforeach
  </tbody>
  <tfoot><tr><td>TOTAL INSTITUTIONNEL</div><td class="c">{{ $stats['departements']->sum('nb_enseignants') }}</div><td class="c">{{ $stats['departements']->sum('nb_activites') }}</div><td class="r" style="font-weight:700;">{{ number_format($stats['departements']->sum('volume_total'),1) }} h</div><td></td><td class="r">100%</div></tr></tfoot>
</table>

{{-- 4. STATISTIQUES MENSUELLES --}}
<div class="sec-title page-break">📅 4. Évolution mensuelle des activités</div>
@php $maxVol = $stats['mensuelles']->max('volume') ?: 1; $moisNoms = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc']; @endphp

<div class="chart-wrap">
  @foreach($stats['mensuelles'] as $m)
  @php $h = $m['volume']>0 ? max(round($m['volume']/$maxVol*70),3) : 2; $isPeak = $m['volume'] == $maxVol && $maxVol>0; @endphp
  <div class="bar-col">
    <div class="bar-rect" style="height:{{ $h }}px;background:{{ $isPeak ? '#1e40af' : ($m['volume']>0 ? '#3b82f6' : '#e2e8f0') }};"></div>
    <div class="bar-lbl" style="font-weight:{{ $isPeak ? '600' : '400' }};color:{{ $isPeak ? '#1e40af' : '#64748b' }};">{{ $moisNoms[$m['mois']-1] }}</div>
  </div>
  @endforeach
</div>

<table>
  <thead><tr><th>Mois</th><th class="c">Activités réalisées</th><th class="r">Volume horaire</th><th style="width:200px;">Tendance mensuelle</th></tr></thead>
  <tbody>
    @foreach($stats['mensuelles'] as $m)
    <tr>
      <td style="font-weight:500;">{{ $m['nom'] }}</div>
      <td class="c">{{ $m['nb_activites'] }}</div>
      <td class="r" style="font-weight:500;">{{ $m['volume']>0 ? number_format($m['volume'],1).' h' : '—' }}</div>
      <td>@if($m['volume']>0)<div class="bar-bg"><div class="bar-fill" style="background:#3b82f6;width:{{ round($m['volume']/$maxVol*100) }}%"></div></div>@endif</div>
    </tr>
    @endforeach
  </tbody>
  <tfoot><tr><td>MOYENNE MENSUELLE</div><td class="c">{{ round($stats['mensuelles']->avg('nb_activites'),0) }}</div><td class="r">{{ round($stats['mensuelles']->avg('volume'),1) }} h</div><td></div></tr></tfoot>
</table>

{{-- 5. ENSEIGNANTS AYANT DÉPASSÉ LEUR CHARGE --}}
<div class="sec-title">⚠️ 5. Enseignants hors quota (seuil {{ $stats['seuil'] }}h)</div>
@if($stats['depasses']->isEmpty())
<div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;padding:14px 18px;font-size:11px;color:#065f46;margin-bottom:14px;text-align:center;">
  ✓ Aucun enseignant n'a dépassé le volume horaire réglementaire pour cette période.
</div>
@else
<table>
  <thead><tr><th>Enseignant</th><th>Grade</th><th>Département</th><th class="r">Volume total</th><th class="r">Dépassement</th><th class="r">Seuil atteint</th></tr></thead>
  <tbody>
    @foreach($stats['depasses'] as $ens)
    @php $vol = (float)($ens->volume_horaire ?? 0); $compl = round($vol - $stats['seuil'], 1); $pct = round($vol / $stats['seuil'] * 100); @endphp
    <tr>
      <td><span style="font-weight:600;">{{ $ens->nom_complet }}</span><br><span style="font-size:9px;color:#64748b;">{{ $ens->utilisateur?->email ?? '' }}</span></div>
      <td><span class="badge badge-gray">{{ $ens->grade?->lib_grd ?? '—' }}</span></div>
      <td style="color:#475569;">{{ $ens->departement?->lib_dep ?? '—' }}</div>
      <td class="r" style="font-weight:700;color:#9a3412;">{{ number_format($vol,1) }} h</div>
      <td class="r"><span class="badge badge-orange">+{{ number_format($compl,1) }} h</span></div>
      <td class="r"><div class="bar-wrap"><div class="bar-bg"><div class="bar-fill" style="background:#9a3412;width:{{ min($pct,100) }}%"></div></div><span class="bar-pct">{{ $pct }}%</span></div></div>
    </tr>
    @endforeach
  </tbody>
  <tfoot><tr><td colspan="3">TOTAL DÉPASSEMENTS INSTITUTIONNELS</div><td class="r" style="font-weight:700;">{{ number_format($stats['depasses']->sum('volume_horaire'),1) }} h</div><td class="r">+{{ number_format($stats['depasses']->sum(fn($e)=>$e->volume_horaire - $stats['seuil']),1) }} h</div><td></div></tr></tfoot>
</table>
@endif

{{-- SIGNATURES --}}
<div class="sig-section">
  <div class="sig-title">VISAS ADMINISTRATIFS</div>
  <div class="sig-row">
    <div class="sig-box"><div class="sig-role">LE SECRÉTAIRE PRINCIPAL</div><div class="sig-who">Nom, date et signature</div><div class="sig-line"></div><div class="sig-date">Date : ___ / ___ / _____</div></div>
    <div class="sig-box"><div class="sig-role">LE DIRECTEUR DES ÉTUDES</div><div class="sig-who">Nom, date et signature</div><div class="sig-line"></div><div class="sig-date">Date : ___ / ___ / _____</div></div>
    <div class="sig-box"><div class="sig-role">LE DIRECTEUR GÉNÉRAL</div><div class="sig-who">Visa et cachet</div><div class="sig-line"></div><div class="sig-date">Date : ___ / ___ / _____</div></div>
  </div>
</div>

<div class="doc-footer">
  <span>UVCI — Direction des Études et de la Scolarité</span>
  <span>Document confidentiel — ne pas diffuser</span>
  <span>Réf : STAT-{{ $annee->lib_anee }}-{{ now()->format('Ymd') }}</span>
</div>

{{-- Boutons --}}
<button class="print-btn no-print" onclick="window.print()">
  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
  </svg>
  Imprimer
</button>
<a href="{{ route('secretaire.statistiques.excel', ['annee_id'=>$annee->id_anee]) }}" class="excel-btn no-print">
  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
  </svg>
  Exporter Excel
</a>

</body>
</html>