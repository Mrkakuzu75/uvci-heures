<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Rapport Statistiques — {{ $annee->lib_anee }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;font-size:11.5px;color:#1a1a1a;background:#fff;padding:28px}
@media print{
  body{padding:0;margin:0}
  .no-print{display:none!important}
  .page-break{page-break-before:always}
  @page{size:A4 portrait;margin:12mm 10mm}
}

/* Header */
.header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px;padding-bottom:10px;}
.logo-zone{display:flex;align-items:center;gap:12px;}
.logo-mark{width:44px;height:44px;background:#00C07F;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:16px;color:#0D1B2A;flex-shrink:0;}
.org-name{font-weight:700;font-size:15px;color:#0D1B2A;}
.org-sub{font-size:9.5px;color:#6B7A8D;font-style:italic;}
.org-contact{font-size:9.5px;color:#6B7A8D;margin-top:1px;}
.meta-right{text-align:right;}
.meta-title{font-weight:700;font-size:17px;color:#0D1B2A;text-transform:uppercase;}
.meta-sub{font-size:10.5px;color:#6B7A8D;margin-top:2px;}
.divider{height:3px;background:linear-gradient(90deg,#0D1B2A 60%,#00C07F 100%);margin-bottom:16px;}

/* Section titre */
.sec-title{font-weight:700;font-size:13px;color:#0D1B2A;text-transform:uppercase;letter-spacing:.6px;
  padding:8px 14px;background:#F4F6FA;border-left:4px solid #00C07F;border-radius:0 6px 6px 0;margin:18px 0 10px;}

/* Tables */
table{width:100%;border-collapse:collapse;margin-bottom:14px;font-size:10.5px;}
thead tr{background:#0D1B2A;}
th{padding:7px 10px;color:#fff;font-size:9.5px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;text-align:left;}
th.r,td.r{text-align:right;}
th.c,td.c{text-align:center;}
td{padding:7px 10px;border-bottom:1px solid #F0F2F5;vertical-align:middle;}
tbody tr:nth-child(even){background:#FAFBFC;}
tfoot tr{background:#E6FBF3;border-top:2px solid #00C07F;}
tfoot td{font-weight:700;font-size:11px;}

.badge-g{display:inline-block;padding:2px 8px;border-radius:8px;font-size:9.5px;font-weight:600;background:#E6FBF3;color:#009962;}
.badge-o{display:inline-block;padding:2px 8px;border-radius:8px;font-size:9.5px;font-weight:600;background:#FFF0EB;color:#FF6B35;}
.badge-b{display:inline-block;padding:2px 8px;border-radius:8px;font-size:9.5px;font-weight:600;background:#EBF3FF;color:#1A6FE0;}

/* Barres inline */
.bar-wrap{display:flex;align-items:center;gap:8px;}
.bar-bg{flex:1;height:8px;background:#F0F2F5;border-radius:4px;overflow:hidden;}
.bar-fill{height:100%;border-radius:4px;}
.bar-pct{width:36px;text-align:right;font-size:10px;font-weight:600;flex-shrink:0;}

/* Graphique mensuel */
.chart-wrap{display:flex;align-items:flex-end;gap:4px;height:80px;border-bottom:1px solid #E2E8F0;margin-bottom:6px;padding:0 4px;}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;}
.bar-rect{width:100%;border-radius:3px 3px 0 0;min-height:2px;}
.bar-lbl{font-size:8px;color:#6B7A8D;margin-top:3px;}

/* KPI résumé */
.kpi-row{display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;}
.kpi-box{flex:1;min-width:110px;border:1px solid #E2E8F0;border-radius:8px;padding:10px 12px;}
.kpi-val{font-weight:700;font-size:18px;color:#0D1B2A;}
.kpi-val.green{color:#009962;}
.kpi-val.orange{color:#FF6B35;}
.kpi-lbl{font-size:9px;color:#6B7A8D;text-transform:uppercase;letter-spacing:.4px;margin-top:2px;}

/* Signatures */
.sig-section{margin-top:28px;padding-top:14px;border-top:2px solid #0D1B2A;}
.sig-title{font-size:10px;font-weight:700;color:#6B7A8D;text-transform:uppercase;letter-spacing:.6px;margin-bottom:14px;}
.sig-row{display:flex;gap:20px;}
.sig-box{flex:1;text-align:center;}
.sig-role{font-size:10.5px;font-weight:700;color:#0D1B2A;margin-bottom:3px;}
.sig-who{font-size:9.5px;color:#6B7A8D;margin-bottom:32px;}
.sig-line{border-bottom:1.5px solid #0D1B2A;margin:0 10px 5px;}
.sig-date{font-size:9px;color:#6B7A8D;}

/* Footer */
.doc-footer{display:flex;justify-content:space-between;align-items:center;margin-top:16px;
  padding-top:8px;border-top:1px solid #E2E8F0;font-size:9.5px;color:#6B7A8D;}

/* Boutons */
.print-btn{position:fixed;bottom:24px;right:24px;display:flex;align-items:center;gap:8px;
  background:#0D1B2A;color:#fff;border:none;padding:11px 22px;border-radius:10px;
  font-size:13px;font-weight:600;cursor:pointer;box-shadow:0 4px 20px rgba(0,0,0,.25);
  font-family:inherit;z-index:999;text-decoration:none;}
.excel-btn{position:fixed;bottom:24px;right:220px;display:flex;align-items:center;gap:8px;
  background:#009962;color:#fff;border:none;padding:11px 22px;border-radius:10px;
  font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;
  box-shadow:0 4px 20px rgba(0,0,0,.2);font-family:inherit;z-index:999;}
</style>
</head>
<body>

{{-- EN-TÊTE --}}
<div class="header">
  <div class="logo-zone">
    <div class="logo-mark">UV</div>
    <div>
      <div class="org-name">Université Virtuelle de Côte d'Ivoire</div>
      <div class="org-sub">EPAST — Décret N°2023-666 du 12 Juillet 2023</div>
      <div class="org-contact">Abidjan Cocody Deux-Plateaux · secretariat@uvci.edu.ci</div>
    </div>
  </div>
  <div class="meta-right">
    <div class="meta-title">Rapport Statistiques Pédagogiques</div>
    <div class="meta-sub">Année académique : {{ $annee->lib_anee }}</div>
    <div class="meta-sub">Édité le : {{ $date }}</div>
    <div class="meta-sub">Réf : STAT-{{ $annee->lib_anee }}-{{ now()->format('Ymd') }}</div>
  </div>
</div>
<div class="divider"></div>

{{-- KPI GLOBAUX --}}
@php
  $totalActivites = $stats['types']->sum('nb_activites');
  $totalVolume    = $stats['types']->sum('volume_total');
  $nbDepasses     = $stats['depasses']->count();
  $moisPic        = $stats['mensuelles']->sortByDesc('volume')->first();
@endphp
<div class="kpi-row">
  <div class="kpi-box"><div class="kpi-val">{{ $totalActivites }}</div><div class="kpi-lbl">Activités enregistrées</div></div>
  <div class="kpi-box"><div class="kpi-val green">{{ number_format($totalVolume,1) }}h</div><div class="kpi-lbl">Volume horaire total</div></div>
  <div class="kpi-box"><div class="kpi-val">{{ $stats['niveaux']->count() }}</div><div class="kpi-lbl">Niveaux utilisés</div></div>
  <div class="kpi-box"><div class="kpi-val orange">{{ $nbDepasses }}</div><div class="kpi-lbl">Enseignants dépassés</div></div>
  <div class="kpi-box"><div class="kpi-val">{{ $moisPic && $moisPic['volume']>0 ? $moisPic['nom'] : '—' }}</div><div class="kpi-lbl">Mois le plus chargé</div></div>
</div>

{{-- 1. RÉPARTITION PAR TYPE D'ACTIVITÉ --}}
<div class="sec-title">1. Répartition par type d'activité</div>
@php $totT = $stats['types']->sum('volume_total') ?: 1; $tColors=['#00C07F','#FF6B35']; @endphp
<table>
  <thead><tr>
    <th>Type d'activité</th>
    <th class="c">Nb activités</th>
    <th class="r">Volume (h)</th>
    <th style="width:200px;">Répartition</th>
    <th class="r">Part (%)</th>
  </tr></thead>
  <tbody>
    @foreach($stats['types'] as $ti => $t)
    <tr>
      <td><span class="badge-{{ $ti===0?'g':'o' }}">{{ $t->lib_typ_act }}</span></td>
      <td class="c">{{ $t->nb_activites }}</td>
      <td class="r" style="font-weight:700;">{{ number_format($t->volume_total,2) }}</td>
      <td>
        <div class="bar-wrap">
          <div class="bar-bg"><div class="bar-fill" style="background:{{ $tColors[$ti%2] }};width:{{ round($t->volume_total/$totT*100) }}%"></div></div>
        </div>
      </td>
      <td class="r">{{ round($t->volume_total/$totT*100,1) }}%</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot><tr>
    <td>TOTAL</td><td class="c">{{ $stats['types']->sum('nb_activites') }}</td>
    <td class="r">{{ number_format($totalVolume,2) }}</td><td></td><td class="r">100%</td>
  </tr></tfoot>
</table>

{{-- 2. RÉPARTITION PAR NIVEAU DE COMPLEXITÉ --}}
<div class="sec-title">2. Répartition par niveau de complexité</div>
@php
  $totN = $stats['niveaux']->sum('volume_total') ?: 1;
  $nivColors=[1=>'#4A90E2',2=>'#00C07F',3=>'#9B59B6'];
  $nivDesc=[1=>'Contenus simples + quiz',2=>'Niv.1 + interactifs',3=>'Serious games'];
@endphp
<table>
  <thead><tr>
    <th>Niveau</th><th>Description</th>
    <th class="c">Nb activités</th>
    <th class="r">Volume (h)</th>
    <th style="width:180px;">Répartition</th>
    <th class="r">Part (%)</th>
  </tr></thead>
  <tbody>
    @foreach($stats['niveaux'] as $niv)
    <tr>
      <td><span style="display:inline-flex;width:26px;height:26px;border-radius:6px;background:{{ $nivColors[$niv->niv_comp]??'#ccc' }}20;border:1px solid {{ $nivColors[$niv->niv_comp]??'#ccc' }}50;align-items:center;justify-content:center;font-weight:700;font-size:11px;color:{{ $nivColors[$niv->niv_comp]??'#ccc' }};">N{{ $niv->niv_comp }}</span></td>
      <td style="color:#6B7A8D;font-size:10px;">{{ $nivDesc[$niv->niv_comp]??'—' }}</td>
      <td class="c">{{ $niv->nb_activites }}</td>
      <td class="r" style="font-weight:700;">{{ number_format($niv->volume_total,2) }}</td>
      <td><div class="bar-wrap"><div class="bar-bg"><div class="bar-fill" style="background:{{ $nivColors[$niv->niv_comp]??'#ccc' }};width:{{ round($niv->volume_total/$totN*100) }}%"></div></div></div></td>
      <td class="r">{{ round($niv->volume_total/$totN*100,1) }}%</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot><tr>
    <td colspan="2">TOTAL</td><td class="c">{{ $stats['niveaux']->sum('nb_activites') }}</td>
    <td class="r">{{ number_format($stats['niveaux']->sum('volume_total'),2) }}</td><td></td><td class="r">100%</td>
  </tr></tfoot>
</table>

{{-- 3. VOLUME PAR DÉPARTEMENT --}}
<div class="sec-title">3. Volume horaire par département</div>
@php
  $totD  = $stats['departements']->sum('volume_total') ?: 1;
  $dColors=['#00C07F','#4A90E2','#FF6B35','#9B59B6','#1ABC9C','#E74C3C'];
@endphp
<table>
  <thead><tr>
    <th>Département</th>
    <th class="c">Enseignants</th>
    <th class="c">Activités</th>
    <th class="r">Volume (h)</th>
    <th style="width:160px;">Répartition</th>
    <th class="r">Part (%)</th>
  </tr></thead>
  <tbody>
    @foreach($stats['departements'] as $di => $dep)
    <tr>
      <td style="font-size:10.5px;">{{ $dep->lib_dep }}</td>
      <td class="c">{{ $dep->nb_enseignants }}</td>
      <td class="c">{{ $dep->nb_activites }}</td>
      <td class="r" style="font-weight:700;color:{{ $dColors[$di%6] }};">{{ number_format($dep->volume_total,2) }}</td>
      <td><div class="bar-wrap"><div class="bar-bg"><div class="bar-fill" style="background:{{ $dColors[$di%6] }};width:{{ round($dep->volume_total/$totD*100) }}%"></div></div></div></td>
      <td class="r">{{ round($dep->volume_total/$totD*100,1) }}%</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot><tr>
    <td>TOTAL</td>
    <td class="c">{{ $stats['departements']->sum('nb_enseignants') }}</td>
    <td class="c">{{ $stats['departements']->sum('nb_activites') }}</td>
    <td class="r">{{ number_format($stats['departements']->sum('volume_total'),2) }}</td>
    <td></td><td class="r">100%</td>
  </tr></tfoot>
</table>

{{-- 4. STATISTIQUES MENSUELLES --}}
<div class="sec-title page-break">4. Évolution mensuelle</div>
@php $maxVol = $stats['mensuelles']->max('volume') ?: 1; @endphp

{{-- Graphique barres --}}
<div class="chart-wrap">
  @foreach($stats['mensuelles'] as $m)
  @php $h = $m['volume']>0 ? max(round($m['volume']/$maxVol*72),3) : 2; $isNow=$m['mois']==now()->month; @endphp
  <div class="bar-col">
    <div class="bar-rect" style="height:{{ $h }}px;background:{{ $m['volume']>0?($isNow?'#009962':'#00C07F'):'#E2E8F0' }};"></div>
    <div class="bar-lbl" style="color:{{ $isNow?'#0D1B2A':'#6B7A8D' }};font-weight:{{ $isNow?'700':'400' }};">{{ substr($m['nom'],0,3) }}</div>
  </div>
  @endforeach
</div>

<table>
  <thead><tr>
    <th>Mois</th>
    <th class="c">Activités</th>
    <th class="r">Volume (h)</th>
    <th style="width:200px;">Progression</th>
  </tr></thead>
  <tbody>
    @foreach($stats['mensuelles'] as $m)
    <tr style="{{ $m['mois']==now()->month?'background:#E6FBF3;':'' }}">
      <td style="font-weight:{{ $m['volume']>0?'600':'400' }};color:{{ $m['volume']>0?'#0D1B2A':'#6B7A8D' }};">
        {{ $m['nom'] }}{{ $m['mois']==now()->month?' ★':'' }}
      </td>
      <td class="c" style="color:{{ $m['nb_activites']>0?'#0D1B2A':'#6B7A8D' }};">{{ $m['nb_activites']>0?$m['nb_activites']:'—' }}</td>
      <td class="r" style="font-weight:{{ $m['volume']>0?'700':'400' }};color:{{ $m['volume']>0?'#009962':'#6B7A8D' }};">{{ $m['volume']>0?number_format($m['volume'],2).'h':'—' }}</td>
      <td>
        @if($m['volume']>0)
        <div class="bar-bg"><div class="bar-fill" style="background:#00C07F;width:{{ round($m['volume']/$maxVol*100) }}%"></div></div>
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
  <tfoot><tr>
    <td>TOTAL</td>
    <td class="c">{{ $stats['mensuelles']->sum('nb_activites') }}</td>
    <td class="r">{{ number_format($stats['mensuelles']->sum('volume'),2) }}h</td>
    <td></td>
  </tr></tfoot>
</table>

{{-- 5. ENSEIGNANTS AYANT DÉPASSÉ LEUR CHARGE --}}
<div class="sec-title">5. Enseignants ayant dépassé {{ $stats['seuil'] }}h</div>
@if($stats['depasses']->isEmpty())
<div style="background:#E6FBF3;border:1px solid #00C07F40;border-radius:8px;padding:12px 16px;font-size:11px;color:#009962;margin-bottom:14px;">
  ✅ Aucun enseignant n'a dépassé la charge de {{ $stats['seuil'] }}h pour cette période.
</div>
@else
<table>
  <thead><tr>
    <th>Enseignant</th><th>Grade</th>
    <th class="r">Volume total (h)</th>
    <th class="r">H. complémentaires</th>
    <th class="r">% de la charge</th>
  </tr></thead>
  <tbody>
    @foreach($stats['depasses'] as $ens)
    @php
      $vol   = (float)($ens->volume_horaire ?? 0);
      $compl = round($vol - $stats['seuil'], 2);
      $pct   = round($vol / $stats['seuil'] * 100);
    @endphp
    <tr>
      <td>
        <div style="font-weight:600;">{{ $ens->nom_complet }}</div>
        <div style="font-size:9.5px;color:#6B7A8D;">{{ $ens->departement?->lib_dep }}</div>
      </td>
      <td><span class="badge-g">{{ $ens->grade?->lib_grd }}</span></td>
      <td class="r" style="font-weight:700;color:#FF6B35;">{{ number_format($vol,2) }}</td>
      <td class="r"><span class="badge-o">+{{ number_format($compl,2) }}h</span></td>
      <td class="r" style="font-weight:700;">{{ $pct }}%</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endif

{{-- SIGNATURES --}}
<div class="sig-section">
  <div class="sig-title">Visa et signatures</div>
  <div class="sig-row">
    <div class="sig-box">
      <div class="sig-role">Le Secrétaire Principal</div>
      <div class="sig-who">Nom et signature</div>
      <div class="sig-line"></div>
      <div class="sig-date">Date : _____ / _____ / _______</div>
    </div>
    <div class="sig-box">
      <div class="sig-role">Le Directeur des Études</div>
      <div class="sig-who">Nom et signature</div>
      <div class="sig-line"></div>
      <div class="sig-date">Date : _____ / _____ / _______</div>
    </div>
    <div class="sig-box">
      <div class="sig-role">Le Directeur Général</div>
      <div class="sig-who" style="color:#FF6B35;font-size:9px;">⬜ Approuvé &nbsp;&nbsp; ⬜ Refusé</div>
      <div class="sig-line"></div>
      <div class="sig-date">Date : _____ / _____ / _______</div>
    </div>
  </div>
</div>

<div class="doc-footer">
  <span>UVCI — Abidjan Cocody Deux-Plateaux · EPAST</span>
  <span>Réf : STAT-{{ $annee->lib_anee }}-{{ now()->format('Ymd') }}</span>
</div>

{{-- Boutons --}}
<button class="print-btn no-print" onclick="window.print()">
  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
  Imprimer / PDF
</button>
<a href="{{ route('secretaire.statistiques.excel', ['annee_id'=>$annee->id_anee]) }}" class="excel-btn no-print">
  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
  Exporter Excel
</a>

</body>
</html>
