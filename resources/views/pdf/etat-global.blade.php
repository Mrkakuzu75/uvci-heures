<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>État global des paiements - {{ $annee->lib_anee }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;font-size:9.5px;color:#1a1a1a;background:#fff;padding:20px}
@media print{
  body{padding:0;margin:0}
  .no-print{display:none!important}
  @page{size:A4 landscape;margin:10mm 8mm}
}

.header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;padding-bottom:8px;}
.logo-mark{width:40px;height:40px;background:linear-gradient(135deg,#5B2E8E 0%,#2E7D32 100%);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:14px;color:#fff;flex-shrink:0;}
.org-name{font-weight:700;font-size:14px;color:#1a3a5c;}
.meta-title{font-weight:700;font-size:15px;color:#1a3a5c;text-transform:uppercase;}

table{width:100%;border-collapse:collapse;margin-top:10px;font-size:8.5px;}
th{background:#1a3a5c;color:#fff;padding:6px 5px;text-align:center;border:1px solid #2c3e50;}
td{padding:5px;border:1px solid #dee2e6;text-align:center;}
tr:nth-child(even){background:#f8f9fa;}
.total-row{background:#e8f5e9;font-weight:700;}
.footer{margin-top:15px;padding-top:6px;border-top:1px solid #dee2e6;font-size:8px;color:#6c757d;text-align:center;}
</style>
</head>
<body>

<div class="header">
  <div style="display:flex;align-items:center;gap:10px;">
    <div class="logo-mark">UV</div>
    <div><div class="org-name">UNIVERSITÉ VIRTUELLE DE CÔTE D'IVOIRE</div><div style="font-size:8px;color:#6c757d;">Gestion des heures d'enseignement</div></div>
  </div>
  <div style="text-align:right;">
    <div class="meta-title">État global des paiements</div>
    <div style="font-size:9px;color:#6c757d;">Année : {{ $annee->lib_anee }} | Édité le : {{ $date }}</div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>N°</th><th>Nom</th><th>Prénom</th><th>Grade</th><th>Statut</th><th>Département</th><th>Taux (FCFA/h)</th>
      <th>Vol. total (h)</th><th>H. normales</th><th>H. compl.</th><th>Mt. normal</th><th>Mt. compl.</th><th>TOTAL (FCFA)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($etat as $index => $ligne)
    @php $ens = $ligne['enseignant']; @endphp
    <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ strtoupper($ens->nom) }}</td>
      <td>{{ $ens->pnom }}</td>
      <td>{{ $ens->grade?->lib_grd ?? '—' }}</td>
      <td>{{ $ens->statut?->lib_stat ?? '—' }}</td>
      <td>{{ $ens->departement?->lib_dep ?? '—' }}</td>
      <td>{{ number_format($ens->tx_horaire,0) }}</td>
      <td>{{ number_format($ligne['volume_total'],1) }}</td>
      <td>{{ number_format($ligne['heures_normales'],1) }}</td>
      <td>{{ number_format($ligne['heures_complementaires'],1) }}</td>
      <td>{{ number_format($ligne['montant_normal'],0) }}</td>
      <td>{{ number_format($ligne['montant_complementaire'],0) }}</td>
      <td><strong>{{ number_format($ligne['montant_total'],0) }}</strong></td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr class="total-row">
      <td colspan="7" style="text-align:right;">TOTAUX</td>
      <td><strong>{{ number_format($totaux['volume'],1) }}</strong></td>
      <td>—</td><td>—</td><td>—</td><td>—</td>
      <td><strong>{{ number_format($totaux['montant'],0) }}</strong></td>
    </tr>
  </tfoot>
</table>

<div class="footer">
  Document généré automatiquement par le système UVCI - Gestion des heures d'enseignement
</div>

<button class="print-btn no-print" onclick="window.print()" style="position:fixed;bottom:20px;right:20px;background:#1a3a5c;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;">Imprimer / PDF</button>

</body>
</html>