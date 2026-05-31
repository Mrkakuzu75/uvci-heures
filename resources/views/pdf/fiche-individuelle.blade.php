<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fiche Récapitulative — {{ $enseignant->nom_complet }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;font-size:11.5px;color:#1a1a1a;background:#fff;padding:28px}
@media print{
  body{padding:0;margin:0}
  .no-print{display:none!important}
  @page{size:A4 portrait;margin:12mm 10mm}
}

/* ── En-tête ── */
.header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;padding-bottom:8px;}
.logo-zone{display:flex;align-items:center;gap:12px;}
.logo-mark{width:44px;height:44px;background:linear-gradient(135deg,#5B2E8E 0%,#2E7D32 100%);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:16px;color:#fff;flex-shrink:0;}
.org-name{font-weight:700;font-size:15px;color:#1a3a5c;}
.org-type{font-size:9.5px;color:#6c757d;font-style:italic;}
.org-contact{font-size:9.5px;color:#6c757d;margin-top:1px;}
.meta-right{text-align:right;line-height:1.5}
.meta-title{font-weight:700;font-size:16px;color:#1a3a5c;text-transform:uppercase;}
.meta-sub{font-size:10px;color:#6c757d;}

/* ── Profil ── */
.profil{display:flex;gap:16px;background:#f8f9fa;border-radius:10px;padding:14px 18px;margin-bottom:14px;align-items:center;}
.profil-avatar{width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#5B2E8E 0%,#2E7D32 100%);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:19px;color:#fff;flex-shrink:0;}
.profil-nom{font-weight:700;font-size:16px;color:#1a3a5c;margin-bottom:4px;}
.profil-tags{display:flex;gap:8px;flex-wrap:wrap;}
.tag{font-size:10px;padding:3px 10px;border-radius:12px;font-weight:500;}
.tag-navy{background:#1a3a5c;color:#fff;}
.tag-green{background:#e8f5e9;color:#2e7d32;}
.tag-blue{background:#e3f2fd;color:#1565c0;}
.tag-gray{background:#f0f2f5;color:#6c757d;}

/* ── KPI ── */
.kpi{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;}
.kb{border:1px solid #dee2e6;border-radius:10px;padding:12px;text-align:center;background:#fff;}
.kv{font-weight:700;font-size:18px;color:#1a3a5c;}
.kv.green{color:#2e7d32;}
.kv.orange{color:#e65100;}
.kl{font-size:9px;color:#6c757d;text-transform:uppercase;letter-spacing:.4px;margin-top:4px;}

/* ── Table ── */
table{width:100%;border-collapse:collapse;margin-bottom:16px;font-size:10.5px;}
thead tr{background:#1a3a5c;}
th{padding:8px 10px;color:#fff;font-size:9.5px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;text-align:left;}
th.r,td.r{text-align:right;}
td{padding:7px 10px;border-bottom:1px solid #e9ecef;vertical-align:middle;}
tbody tr:nth-child(even){background:#f8f9fa;}
tfoot tr{background:#e8f5e9;}
tfoot td{font-weight:700;font-size:11px;}
.badge{display:inline-block;padding:2px 8px;border-radius:12px;font-size:9px;font-weight:500;}
.badge-creation{background:#e3f2fd;color:#1565c0;}
.badge-maj{background:#fff3e0;color:#e65100;}

/* ── Récapitulatif ── */
.recap{background:#f8f9fa;border-radius:10px;padding:16px 20px;margin-bottom:20px;}
.recap-title{font-weight:700;font-size:12px;color:#1a3a5c;margin-bottom:12px;text-transform:uppercase;letter-spacing:.5px;}
.recap-line{display:flex;justify-content:space-between;padding:6px 0;}
.recap-line:last-of-type{border-top:1px solid #dee2e6;margin-top:6px;padding-top:10px;font-weight:700;font-size:13px;color:#2e7d32;}

/* ── Signatures ── */
.sig-section{margin-top:24px;padding-top:16px;border-top:1px solid #dee2e6;}
.sig-title{font-size:10px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.6px;margin-bottom:16px;}
.sig-row{display:flex;gap:20px;}
.sig-box{flex:1;text-align:center;}
.sig-role{font-size:10.5px;font-weight:700;color:#1a3a5c;margin-bottom:4px;}
.sig-who{font-size:9.5px;color:#6c757d;margin-bottom:30px;}
.sig-line{border-bottom:1px solid #1a3a5c;margin:0 8px 6px;}
.sig-name{font-size:9px;font-weight:600;color:#1a3a5c;margin-bottom:2px;}
.sig-date{font-size:8.5px;color:#6c757d;}

/* ── Footer ── */
.footer{display:flex;justify-content:space-between;align-items:center;margin-top:16px;padding-top:8px;border-top:1px solid #e9ecef;font-size:9px;color:#6c757d;}

/* ── Boutons ── */
.print-btn{position:fixed;bottom:24px;right:24px;display:flex;align-items:center;gap:8px;background:#1a3a5c;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;box-shadow:0 2px 10px rgba(0,0,0,.15);font-family:inherit;z-index:999;}
.excel-btn{position:fixed;bottom:24px;right:200px;display:flex;align-items:center;gap:8px;background:#2e7d32;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;box-shadow:0 2px 10px rgba(0,0,0,.15);font-family:inherit;z-index:999;}
</style>
</head>
<body>

{{-- ── En-tête ── --}}
<div class="header">
  <div class="logo-zone">
    <div class="logo-mark">UV</div>
    <div>
      <div class="org-name">UNIVERSITÉ VIRTUELLE DE CÔTE D'IVOIRE</div>
      <div class="org-type">Système de Gestion des Heures d'Enseignement</div>
      <div class="org-contact">secretariat@uvci.edu.ci · www.uvci.edu.ci</div>
    </div>
  </div>
  <div class="meta-right">
    <div class="meta-title">FICHE INDIVIDUELLE</div>
    <div class="meta-sub">Année académique : {{ $annee->lib_anee }}</div>
    <div class="meta-sub">Édité le : {{ $date }}</div>
  </div>
</div>

{{-- ── Profil ── --}}
<div class="profil">
  <div class="profil-avatar">{{ $enseignant->initiales }}</div>
  <div style="flex:1;">
    <div class="profil-nom">{{ strtoupper($enseignant->nom) }} {{ $enseignant->pnom }}</div>
    <div class="profil-tags">
      <span class="tag tag-navy">{{ $enseignant->grade?->lib_grd ?? '—' }}</span>
      @if(strtolower($enseignant->statut?->lib_stat??'') === 'permanent')
        <span class="tag tag-green">Permanent</span>
      @else
        <span class="tag" style="background:#fff3e0;color:#e65100;">Vacataire</span>
      @endif
      <span class="tag tag-blue">{{ $enseignant->departement?->lib_dep ?? '—' }}</span>
      @if($enseignant->tel)
        <span class="tag tag-gray">{{ $enseignant->tel }}</span>
      @endif
    </div>
  </div>
  <div style="text-align:right;flex-shrink:0;">
    <div style="font-size:10px;color:#6c757d;">Taux horaire</div>
    <div style="font-weight:700;font-size:20px;color:#1a3a5c;">
      {{ number_format($enseignant->tx_horaire,0,'.',' ') }}
      <span style="font-size:10px;font-weight:400;color:#6c757d;"> FCFA/h</span>
    </div>
  </div>
</div>

{{-- ── KPI ── --}}
<div class="kpi">
  <div class="kb">
    <div class="kv">{{ number_format($volumeTotal,0) }}</div>
    <div class="kl">Heures effectuées</div>
  </div>
  <div class="kb">
    <div class="kv">{{ $activites->groupBy(fn($a)=>$a->ressource?->sequence?->cours?->id_crs)->count() }}</div>
    <div class="kl">Cours assurés</div>
  </div>
  <div class="kb">
    <div class="kv orange">{{ number_format($heuresComplementaires,0) }}</div>
    <div class="kl">Heures complémentaires</div>
  </div>
</div>

{{-- ── Table activités ── --}}
<table>
  <thead>
    <tr>
      <th>N°</th>
      <th>Intitulé du cours</th>
      <th>Filière</th>
      <th>Niveau</th>
      <th>Type</th>
      <th class="r">Volume (h)</th>
    </tr>
  </thead>
  <tbody>
    @forelse($activites as $index => $act)
    <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $act->ressource?->sequence?->cours?->intit ?? '—' }}</td>
      <td>{{ $act->ressource?->sequence?->cours?->filre ?? '—' }}</td>
      <td>{{ $act->ressource?->sequence?->cours?->niv ?? '—' }}</td>
      <td>
        @if($act->id_typ_act == 1)
          <span class="badge badge-creation">Cours</span>
        @else
          <span class="badge badge-maj">MAJ</span>
        @endif
      </td>
      <td class="r">{{ number_format($act->v_hor,0) }} h</div>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;padding:20px;">Aucune activité validée</div></tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr><td colspan="5" style="text-align:right;font-weight:700;">TOTAL HEURES</div><td class="r">{{ number_format($volumeTotal,0) }} h</div></tr>
  </tfoot>
</table>

{{-- ── Récapitulatif financier ── --}}
<div class="recap">
  <div class="recap-title">Récapitulatif financier</div>
  <div class="recap-line">
    <span>Total heures effectuées</span>
    <span>{{ number_format($volumeTotal,0) }} h</span>
  </div>
  <div class="recap-line">
    <span>Taux horaire</span>
    <span>{{ number_format($enseignant->tx_horaire,0) }} FCFA/h</span>
  </div>
  @if($heuresComplementaires > 0)
  <div class="recap-line">
    <span>Heures complémentaires (majoration 150%)</span>
    <span>{{ number_format($montantCompl,0) }} FCFA</span>
  </div>
  @endif
  <div class="recap-line">
    <span>TOTAL BRUT ESTIMÉ</span>
    <span>{{ number_format($montantTotal,0) }} FCFA</span>
  </div>
</div>

{{-- ── Signatures ── --}}
<div class="sig-section">
  <div class="sig-title">VALIDATIONS</div>
  <div class="sig-row">
    <div class="sig-box">
      <div class="sig-role">L'ENSEIGNANT</div>
      <div class="sig-who">{{ strtoupper($enseignant->nom) }} {{ $enseignant->pnom }}</div>
      <div class="sig-line"></div>
      <div class="sig-name">Signature</div>
      <div class="sig-date">Date : _____ / _____ / _____</div>
    </div>
    <div class="sig-box">
      <div class="sig-role">LE SECRÉTAIRE</div>
      <div class="sig-who">Nom et signature</div>
      <div class="sig-line"></div>
      <div class="sig-name">Signature</div>
      <div class="sig-date">Date : _____ / _____ / _____</div>
    </div>
    <div class="sig-box">
      <div class="sig-role">L'ADMINISTRATION</div>
      <div class="sig-who">Nom et signature</div>
      <div class="sig-line"></div>
      <div class="sig-name">Signature &amp; Cachet</div>
      <div class="sig-date">Date : _____ / _____ / _____</div>
    </div>
  </div>
</div>

{{-- ── Footer ── --}}
<div class="footer">
  <span>© {{ date('Y') }} Université Virtuelle de Côte d'Ivoire - Tous droits réservés</span>
  <span>Réf : FICHE-{{ strtoupper(substr($enseignant->nom,0,3)) }}-{{ $annee->lib_anee }}</span>
</div>

{{-- Boutons --}}
<button class="print-btn no-print" onclick="window.print()">
  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
  </svg>
  Imprimer / PDF
</button>

@if(auth()->user()?->isEnseignant())
<a href="{{ route('enseignant.excel-recap', ['annee_id'=>$annee->id_anee]) }}" class="excel-btn no-print">
@else
<a href="{{ route('secretaire.paiements.excel-fiche', [$enseignant->id_ens, 'annee_id'=>$annee->id_anee]) }}" class="excel-btn no-print">
@endif
  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
  </svg>
  Exporter Excel
</a>

</body>
</html>