<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
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
.header{display:flex;align-items:flex-start;justify-content:space-between;
  margin-bottom:6px;padding-bottom:10px;}
.logo-zone{display:flex;align-items:center;gap:12px;}
.logo-mark{width:44px;height:44px;background:#00C07F;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-weight:900;font-size:16px;color:#0D1B2A;flex-shrink:0;}
.org-name{font-weight:700;font-size:15px;color:#0D1B2A;}
.org-type{font-size:9.5px;color:#6B7A8D;font-style:italic;}
.org-contact{font-size:9.5px;color:#6B7A8D;margin-top:1px;}
.meta-right{text-align:right;line-height:1.6}
.meta-title{font-weight:700;font-size:17px;color:#0D1B2A;text-transform:uppercase;}
.meta-sub{font-size:10.5px;color:#6B7A8D;}
.divider{height:3px;background:linear-gradient(90deg,#0D1B2A 60%,#00C07F 100%);margin-bottom:14px;}

/* ── Profil ── */
.profil{display:flex;gap:16px;background:#F4F6FA;border-radius:10px;
  padding:14px 18px;margin-bottom:14px;align-items:center;}
.profil-avatar{width:50px;height:50px;border-radius:12px;background:#00C07F;
  display:flex;align-items:center;justify-content:center;
  font-weight:800;font-size:19px;color:#0D1B2A;flex-shrink:0;}
.profil-nom{font-weight:700;font-size:16px;color:#0D1B2A;margin-bottom:6px;}
.profil-tags{display:flex;gap:8px;flex-wrap:wrap;}
.tag{font-size:10px;padding:2px 9px;border-radius:10px;font-weight:500;}
.t-navy{background:#0D1B2A;color:#fff;}
.t-green{background:#E6FBF3;color:#009962;}
.t-blue{background:#EBF3FF;color:#1A6FE0;}
.t-orange{background:#FFF0EB;color:#FF6B35;}
.t-gray{background:#F0F2F5;color:#6B7A8D;}

/* ── KPI ── */
.kpi{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:14px;}
.kb{border:1px solid #E2E8F0;border-radius:8px;padding:10px;text-align:center;}
.kv{font-weight:700;font-size:18px;color:#0D1B2A;}
.kv.green{color:#009962;}
.kv.orange{color:#FF6B35;}
.kl{font-size:9px;color:#6B7A8D;text-transform:uppercase;letter-spacing:.4px;margin-top:3px;}

/* ── Table ── */
table{width:100%;border-collapse:collapse;margin-bottom:12px;font-size:10.5px;}
thead tr{background:#0D1B2A;}
th{padding:7px 9px;color:#fff;font-size:9.5px;font-weight:600;
  text-transform:uppercase;letter-spacing:.4px;text-align:left;}
th.r,td.r{text-align:right;}
td{padding:7px 9px;border-bottom:1px solid #F0F2F5;vertical-align:middle;}
tbody tr:nth-child(even){background:#FAFBFC;}
tfoot tr{background:#E6FBF3;}
tfoot td{font-weight:700;font-size:11px;}
.bg{display:inline-block;padding:2px 7px;border-radius:8px;
  font-size:9.5px;font-weight:600;background:#E6FBF3;color:#009962;}
.bo{display:inline-block;padding:2px 7px;border-radius:8px;
  font-size:9.5px;font-weight:600;background:#FFF0EB;color:#FF6B35;}

/* ── Calcul ── */
.calc{background:#F4F6FA;border-radius:8px;padding:10px 14px;
  font-size:10.5px;margin-bottom:20px;border-left:3px solid #0D1B2A;}
.cr{display:flex;justify-content:space-between;padding:2px 0;}
.ct{font-weight:700;font-size:13px;color:#009962;
  border-top:1px solid #E2E8F0;margin-top:6px;padding-top:6px;}

/* ══ SIGNATURES ═══════════════════════════════════════════ */
.sig-section{
  margin-top:28px;
  padding-top:16px;
  border-top:2px solid #0D1B2A;
}
.sig-title{
  font-size:10px;font-weight:700;color:#6B7A8D;
  text-transform:uppercase;letter-spacing:.6px;
  margin-bottom:18px;
}
.sig-row{display:flex;gap:16px;}
.sig-box{flex:1;text-align:center;}

/* Rôle en haut */
.sig-role{
  font-size:10.5px;font-weight:700;color:#0D1B2A;
  margin-bottom:3px;
}
/* Nom indicatif */
.sig-who{
  font-size:9.5px;color:#6B7A8D;
  margin-bottom:36px; /* espace pour écrire */
}
/* Ligne de signature */
.sig-line{
  border-bottom:1.5px solid #0D1B2A;
  margin:0 8px 6px;
}
/* Nom imprimé sous la ligne (pour l'enseignant) */
.sig-name{
  font-size:9.5px;font-weight:600;color:#0D1B2A;
  margin-bottom:2px;
}
/* Date */
.sig-date{
  font-size:9px;color:#6B7A8D;
}
/* Case approbation DG */
.sig-approve{
  display:flex;gap:16px;justify-content:center;
  margin-bottom:36px;font-size:9.5px;color:#6B7A8D;
}
.sig-check{
  display:inline-flex;align-items:center;gap:5px;
}
.sig-check span{
  width:13px;height:13px;border:1.5px solid #6B7A8D;
  display:inline-block;border-radius:2px;flex-shrink:0;
}

/* ── Footer ── */
.footer{
  display:flex;justify-content:space-between;
  align-items:center;margin-top:16px;
  padding-top:8px;border-top:1px solid #E2E8F0;
  font-size:9.5px;color:#6B7A8D;
}

/* ── Boutons ── */
.print-btn{
  position:fixed;bottom:24px;right:24px;
  display:flex;align-items:center;gap:8px;
  background:#0D1B2A;color:#fff;border:none;
  padding:11px 22px;border-radius:10px;font-size:13px;
  font-weight:600;cursor:pointer;
  box-shadow:0 4px 20px rgba(0,0,0,.25);font-family:inherit;z-index:999;
}
.excel-btn{
  position:fixed;bottom:24px;right:220px;
  display:flex;align-items:center;gap:8px;
  background:#009962;color:#fff;border:none;
  padding:11px 22px;border-radius:10px;font-size:13px;
  font-weight:600;cursor:pointer;text-decoration:none;
  box-shadow:0 4px 20px rgba(0,0,0,.2);font-family:inherit;z-index:999;
}
</style>
</head>
<body>

{{-- ── En-tête ── --}}
<div class="header">
  <div class="logo-zone">
    <div class="logo-mark">UV</div>
    <div>
      <div class="org-name">Université Virtuelle de Côte d'Ivoire</div>
      <div class="org-type">EPAST — Décret N°2023-666 du 12 Juillet 2023</div>
      <div class="org-contact">Abidjan Cocody Deux-Plateaux · secretariat@uvci.edu.ci · www.uvci.edu.ci</div>
    </div>
  </div>
  <div class="meta-right">
    <div class="meta-title">Fiche Récapitulative</div>
    <div class="meta-sub">Heures pédagogiques — {{ $annee->lib_anee }}</div>
    <div class="meta-sub">Édité le : {{ $date }}</div>
    <div class="meta-sub">Réf : FICHE-{{ strtoupper(substr($enseignant->nom,0,3)) }}-{{ $annee->lib_anee }}-{{ now()->format('Ymd') }}</div>
  </div>
</div>
<div class="divider"></div>

{{-- ── Profil ── --}}
<div class="profil">
  <div class="profil-avatar">{{ $enseignant->initiales }}</div>
  <div style="flex:1;">
    <div class="profil-nom">{{ strtoupper($enseignant->nom) }} {{ $enseignant->pnom }}</div>
    <div class="profil-tags">
      <span class="tag t-navy">{{ $enseignant->grade?->lib_grd }}</span>
      @if(strtolower($enseignant->statut?->lib_stat??'') === 'permanent')
        <span class="tag t-blue">Permanent</span>
      @else
        <span class="tag t-orange">Vacataire</span>
      @endif
      <span class="tag t-green">{{ $enseignant->departement?->lib_dep }}</span>
      @if($enseignant->tel)
        <span class="tag t-gray">{{ $enseignant->tel }}</span>
      @endif
    </div>
  </div>
  <div style="text-align:right;flex-shrink:0;">
    <div style="font-size:11px;color:#6B7A8D;">Taux horaire</div>
    <div style="font-weight:700;font-size:20px;color:#0D1B2A;">
      {{ number_format($enseignant->tx_horaire,0,'.',' ') }}
      <span style="font-size:11px;font-weight:400;color:#6B7A8D;"> FCFA/h</span>
    </div>
  </div>
</div>

{{-- ── KPI ── --}}
<div class="kpi">
  <div class="kb">
    <div class="kv">{{ number_format($volumeTotal,2) }}</div>
    <div class="kl">Volume total (h)</div>
  </div>
  <div class="kb">
    <div class="kv">{{ number_format(min($volumeTotal,192),2) }}</div>
    <div class="kl">Heures normales</div>
  </div>
  <div class="kb">
    <div class="kv orange">{{ number_format($heuresComplementaires,2) }}</div>
    <div class="kl">H. complémentaires</div>
  </div>
</div>

{{-- ── Table activités ── --}}
<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Cours</th>
      <th>Séquence</th>
      <th>Type</th>
      <th>Niv.</th>
      <th class="r">Volume (h)</th>
    </tr>
  </thead>
  <tbody>
    @forelse($activites as $act)
    <tr>
      <td style="white-space:nowrap;color:#6B7A8D;">{{ $act->date_act->format('d/m/Y') }}</td>
      <td style="max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
        {{ $act->ressource?->sequence?->cours?->intit ?? '—' }}
      </td>
      <td style="max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#6B7A8D;">
        {{ $act->ressource?->sequence?->ttre_seq ?? '—' }}
      </td>
      <td>
        @if($act->id_typ_act==1)
          <span class="bg">Création</span>
        @else
          <span class="bo">MAJ</span>
        @endif
      </td>
      <td style="text-align:center;font-weight:700;">N{{ $act->ressource?->niv_comp??'?' }}</td>
      <td class="r" style="font-weight:700;color:#009962;">{{ number_format($act->v_hor,2) }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="6" style="text-align:center;padding:20px;color:#6B7A8D;">
        Aucune activité enregistrée pour cette période
      </td>
    </tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" style="padding:8px 9px;">TOTAL</td>
      <td class="r" style="color:#009962;font-size:13px;">{{ number_format($volumeTotal,2) }}h</td>
    </tr>
  </tfoot>
</table>

{{-- ── Détail calcul ── --}}
<div class="calc">
  <div style="font-weight:600;color:#0D1B2A;margin-bottom:6px;">Détail du calcul de la rémunération</div>
  <div class="cr">
    <span>
      Heures normales
      ({{ number_format(min($volumeTotal,192),2) }}h × {{ number_format($enseignant->tx_horaire,0,'.',' ') }} FCFA/h)
    </span>
    <strong>{{ number_format($montantNormal,0,'.',' ') }} FCFA</strong>
  </div>
  @if($heuresComplementaires > 0)
  <div class="cr">
    <span>
      Heures complémentaires — majoration 150%
      ({{ number_format($heuresComplementaires,2) }}h × {{ number_format($enseignant->tx_horaire*1.5,0,'.',' ') }} FCFA/h)
    </span>
    <strong>{{ number_format($montantCompl,0,'.',' ') }} FCFA</strong>
  </div>
  @endif
  <div class="cr ct">
    <span>MONTANT TOTAL À PAYER</span>
    <span>{{ number_format($montantTotal + $montantCompl,0,'.',' ') }} FCFA</span>
  </div>
</div>

{{-- ══════════ SIGNATURES ══════════ --}}
<div class="sig-section">
  <div class="sig-title">Visa et signatures</div>
  <div class="sig-row">

    {{-- Signature 1 : L'Enseignant --}}
    <div class="sig-box">
      <div class="sig-role">L'Enseignant(e)</div>
      <div class="sig-who">{{ strtoupper($enseignant->nom) }} {{ $enseignant->pnom }}</div>
      <div class="sig-line"></div>
      <div class="sig-name">Signature</div>
      <div class="sig-date">Date : _____ / _____ / _______</div>
    </div>

    {{-- Signature 2 : Le Secrétaire Principal --}}
    <div class="sig-box">
      <div class="sig-role">Le Secrétaire Principal</div>
      <div class="sig-who">Nom et signature</div>
      <div class="sig-line"></div>
      <div class="sig-name">Signature</div>
      <div class="sig-date">Date : _____ / _____ / _______</div>
    </div>

    {{-- Signature 3 : Le Directeur Général --}}
    <div class="sig-box">
      <div class="sig-role">Le Directeur Général</div>
      <div class="sig-approve">
        <span class="sig-check"><span></span> Approuvé</span>
        <span class="sig-check"><span></span> Refusé</span>
      </div>
      <div class="sig-line"></div>
      <div class="sig-name">Signature &amp; Cachet</div>
      <div class="sig-date">Date : _____ / _____ / _______</div>
    </div>

  </div>
</div>

{{-- ── Footer ── --}}
<div class="footer">
  <span>UVCI — Université Virtuelle de Côte d'Ivoire · Abidjan Cocody Deux-Plateaux</span>
  <span>Réf : FICHE-{{ strtoupper(substr($enseignant->nom,0,3)) }}-{{ $annee->lib_anee }}-{{ now()->format('Ymd') }}</span>
</div>

{{-- Boutons (cachés à l'impression) --}}
<button class="print-btn no-print" onclick="window.print()">
  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2
         m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5
         a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
  </svg>
  Imprimer / PDF
</button>

@if(auth()->user()?->isEnseignant())
<a href="{{ route('enseignant.excel-recap', ['annee_id'=>$annee->id_anee]) }}"
   class="excel-btn no-print">
@else
<a href="{{ route('secretaire.paiements.excel-fiche', [$enseignant->id_ens, 'annee_id'=>$annee->id_anee]) }}"
   class="excel-btn no-print">
@endif
  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
         a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
  </svg>
  Exporter Excel
</a>

</body>
</html>
