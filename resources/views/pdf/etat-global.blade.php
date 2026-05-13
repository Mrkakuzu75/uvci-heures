<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>État de paiement — {{ $annee->lib_anee }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;color:#1a1a1a;background:#fff;padding:20px}
@media print{
  body{padding:0}
  .no-print{display:none!important}
  .page-break{page-break-before:always}
  table{page-break-inside:auto}
  tr{page-break-inside:avoid}
}

/* Header */
.doc-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;padding-bottom:16px;border-bottom:3px solid #0D1B2A}
.logo-wrap{display:flex;align-items:center;gap:12px}
.logo-mark{width:40px;height:40px;background:#00C07F;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;color:#0D1B2A;flex-shrink:0}
.org-name{font-weight:700;font-size:16px;color:#0D1B2A}
.org-sub{font-size:11px;color:#6B7A8D;margin-top:2px}
.doc-meta{text-align:right}
.doc-title{font-weight:700;font-size:18px;color:#0D1B2A}
.doc-annee{font-size:12px;color:#6B7A8D;margin-top:3px}
.doc-date{font-size:11px;color:#6B7A8D;margin-top:2px}

/* KPI */
.kpi-row{display:flex;gap:12px;margin-bottom:20px}
.kpi{flex:1;border:1px solid #E2E8F0;border-radius:8px;padding:12px 16px;background:#FAFBFC}
.kpi-val{font-weight:700;font-size:18px;color:#0D1B2A}
.kpi-lbl{font-size:10px;color:#6B7A8D;margin-top:2px;text-transform:uppercase;letter-spacing:.5px}

/* Table */
table{width:100%;border-collapse:collapse;margin-bottom:20px}
thead tr{background:#0D1B2A}
th{padding:9px 10px;text-align:left;font-size:10.5px;font-weight:600;color:#fff;text-transform:uppercase;letter-spacing:.5px}
th.r{text-align:right}
td{padding:9px 10px;border-bottom:1px solid #F0F2F5;font-size:11.5px;vertical-align:middle}
td.r{text-align:right}
tbody tr:nth-child(even){background:#FAFBFC}
tbody tr:hover{background:#F0FBF6}
tfoot tr{background:#E6FBF3;border-top:2px solid #00C07F}
tfoot td{font-weight:700;font-size:12px;color:#0D1B2A}

/* Badges */
.b-green{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600;background:#E6FBF3;color:#009962}
.b-orange{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600;background:#FFF0EB;color:#FF6B35}
.montant{font-weight:700;color:#009962}

/* Footer */
.doc-footer{margin-top:24px;padding-top:12px;border-top:1px solid #E2E8F0;display:flex;justify-content:space-between;font-size:10px;color:#6B7A8D}

/* Signature */
.signatures{display:flex;justify-content:space-between;margin-top:40px;gap:20px}
.sig-box{flex:1;text-align:center}
.sig-line{border-bottom:1px solid #0D1B2A;margin-bottom:6px;height:40px}
.sig-label{font-size:10px;color:#6B7A8D}

/* Print button */
.print-btn{position:fixed;bottom:24px;right:24px;background:#0D1B2A;color:#fff;border:none;padding:12px 24px;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,.2);display:flex;align-items:center;gap:8px;font-family:inherit}
.print-btn:hover{background:#1A2E42}
</style>
</head>
<body>

{{-- Header --}}
<div class="doc-header">
  <div class="logo-wrap">
    <div class="logo-mark">UV</div>
    <div>
      <div class="org-name">UVCI</div>
      <div class="org-sub">Université Virtuelle de Côte d'Ivoire</div>
    </div>
  </div>
  <div class="doc-meta">
    <div class="doc-title">ÉTAT DE PAIEMENT</div>
    <div class="doc-annee">Année académique : {{ $annee->lib_anee }}</div>
    <div class="doc-date">Édité le : {{ now()->format('d/m/Y à H:i') }}</div>
  </div>
</div>

{{-- KPI --}}
<div class="kpi-row">
  <div class="kpi">
    <div class="kpi-val">{{ $totaux['nb_ens'] }}</div>
    <div class="kpi-lbl">Enseignants actifs</div>
  </div>
  <div class="kpi">
    <div class="kpi-val">{{ number_format($totaux['volume'],1) }}h</div>
    <div class="kpi-lbl">Volume horaire total</div>
  </div>
  <div class="kpi">
    <div class="kpi-val">{{ number_format($totaux['montant'],0,'.',' ') }} FCFA</div>
    <div class="kpi-lbl">Montant total à payer</div>
  </div>
</div>

{{-- Table --}}
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Enseignant</th>
      <th>Grade</th>
      <th>Statut</th>
      <th class="r">Vol. total (h)</th>
      <th class="r">H. normales</th>
      <th class="r">H. complém.</th>
      <th class="r">Taux FCFA/h</th>
      <th class="r">Montant (FCFA)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($etat as $idx => $ligne)
    @php $ens = $ligne['enseignant']; @endphp
    <tr>
      <td style="color:#6B7A8D;font-size:10px;">{{ $idx + 1 }}</td>
      <td>
        <div style="font-weight:600;">{{ $ens->nom_complet }}</div>
        <div style="font-size:10px;color:#6B7A8D;">{{ $ens->departement?->lib_dep }}</div>
      </td>
      <td><span class="b-green">{{ $ens->grade?->lib_grd }}</span></td>
      <td>
        @if(strtolower($ens->statut?->lib_stat??'') === 'permanent')
          <span style="color:#1A6FE0;font-size:10px;font-weight:600;">Permanent</span>
        @else
          <span style="color:#FF6B35;font-size:10px;font-weight:600;">Vacataire</span>
        @endif
      </td>
      <td class="r" style="font-weight:700;">{{ number_format($ligne['volume_total'],2) }}</td>
      <td class="r" style="color:#6B7A8D;">{{ number_format($ligne['heures_normales'],2) }}</td>
      <td class="r">
        @if($ligne['heures_complementaires'] > 0)
          <span class="b-orange">{{ number_format($ligne['heures_complementaires'],2) }}</span>
        @else —
        @endif
      </td>
      <td class="r" style="color:#6B7A8D;">{{ number_format($ens->tx_horaire,0) }}</td>
      <td class="r montant">{{ number_format($ligne['montant_total'],0,'.',' ') }}</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="4" style="padding:10px;">TOTAL GÉNÉRAL</td>
      <td class="r">{{ number_format($totaux['volume'],2) }}</td>
      <td colspan="3"></td>
      <td class="r" style="font-size:14px;color:#009962;">{{ number_format($totaux['montant'],0,'.',' ') }}</td>
    </tr>
  </tfoot>
</table>

{{-- Note --}}
<div style="font-size:10.5px;color:#6B7A8D;background:#FFF8E1;border:1px solid #FFE082;border-radius:6px;padding:10px 14px;margin-bottom:24px;">
  <strong>Note :</strong> Les heures complémentaires (au-delà de 192h) sont majorées à 150% du taux horaire.
</div>

{{-- Signatures --}}
<div class="signatures">
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-label">Le Secrétaire Principal</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-label">Le Directeur des Études</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-label">Le Directeur Général</div>
  </div>
</div>

<div class="doc-footer">
  <span>UVCI — Université Virtuelle de Côte d'Ivoire</span>
  <span>Document généré automatiquement — {{ now()->format('d/m/Y') }}</span>
</div>

{{-- Bouton impression --}}
<button class="print-btn no-print" onclick="window.print()">
  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
  Imprimer / Enregistrer en PDF
</button>

</body>
</html>
