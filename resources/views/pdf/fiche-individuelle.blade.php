<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fiche — {{ $enseignant->nom_complet }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;color:#1a1a1a;background:#fff;padding:24px}
@media print{
  body{padding:0}
  .no-print{display:none!important}
}

.doc-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;padding-bottom:14px;border-bottom:3px solid #0D1B2A}
.logo-wrap{display:flex;align-items:center;gap:12px}
.logo-mark{width:40px;height:40px;background:#00C07F;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;color:#0D1B2A}
.org-name{font-weight:700;font-size:16px;color:#0D1B2A}
.org-sub{font-size:11px;color:#6B7A8D;margin-top:2px}
.doc-meta{text-align:right}
.doc-title{font-weight:700;font-size:17px;color:#0D1B2A}
.doc-sub{font-size:12px;color:#6B7A8D;margin-top:3px}

/* Profil enseignant */
.profil{display:flex;gap:20px;background:#F4F6FA;border-radius:10px;padding:16px 20px;margin-bottom:20px;align-items:flex-start}
.profil-avatar{width:52px;height:52px;border-radius:12px;background:#00C07F;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;color:#0D1B2A;flex-shrink:0}
.profil-info{flex:1}
.profil-nom{font-weight:700;font-size:16px;color:#0D1B2A}
.profil-details{display:flex;gap:16px;margin-top:6px;flex-wrap:wrap}
.profil-item{font-size:11px;color:#6B7A8D;display:flex;align-items:center;gap:4px}
.profil-item strong{color:#0D1B2A}

/* Résumé volumes */
.resume-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px}
.resume-box{border:1px solid #E2E8F0;border-radius:8px;padding:12px;text-align:center;background:#fff}
.resume-val{font-weight:700;font-size:20px;color:#0D1B2A}
.resume-val.green{color:#009962}
.resume-val.orange{color:#FF6B35}
.resume-lbl{font-size:10px;color:#6B7A8D;margin-top:3px;text-transform:uppercase;letter-spacing:.4px}

/* Table activités */
table{width:100%;border-collapse:collapse;margin-bottom:20px}
thead tr{background:#0D1B2A}
th{padding:8px 10px;text-align:left;font-size:10px;font-weight:600;color:#fff;text-transform:uppercase;letter-spacing:.5px}
th.r{text-align:right}
td{padding:8px 10px;border-bottom:1px solid #F0F2F5;font-size:11px;vertical-align:middle}
td.r{text-align:right}
tbody tr:nth-child(even){background:#FAFBFC}
tfoot tr{background:#E6FBF3;border-top:2px solid #00C07F}
tfoot td{font-weight:700;font-size:11.5px}

.b-green{display:inline-block;padding:2px 7px;border-radius:8px;font-size:10px;font-weight:600;background:#E6FBF3;color:#009962}
.b-orange{display:inline-block;padding:2px 7px;border-radius:8px;font-size:10px;font-weight:600;background:#FFF0EB;color:#FF6B35}

/* Signature */
.signatures{display:flex;justify-content:space-between;margin-top:36px;gap:24px}
.sig-box{flex:1;text-align:center}
.sig-line{border-bottom:1px solid #0D1B2A;margin-bottom:6px;height:36px}
.sig-label{font-size:10px;color:#6B7A8D}

.doc-footer{margin-top:20px;padding-top:10px;border-top:1px solid #E2E8F0;display:flex;justify-content:space-between;font-size:10px;color:#6B7A8D}

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
    <div class="doc-title">FICHE RÉCAPITULATIVE</div>
    <div class="doc-sub">Année académique : {{ $annee->lib_anee }}</div>
    <div class="doc-sub">Édité le : {{ now()->format('d/m/Y à H:i') }}</div>
  </div>
</div>

{{-- Profil --}}
<div class="profil">
  <div class="profil-avatar">{{ $enseignant->initiales }}</div>
  <div class="profil-info">
    <div class="profil-nom">{{ $enseignant->nom_complet }}</div>
    <div class="profil-details">
      <span class="profil-item"><strong>Grade :</strong> {{ $enseignant->grade?->lib_grd }}</span>
      <span class="profil-item"><strong>Statut :</strong> {{ $enseignant->statut?->lib_stat }}</span>
      <span class="profil-item"><strong>Département :</strong> {{ $enseignant->departement?->lib_dep }}</span>
      <span class="profil-item"><strong>Taux :</strong> {{ number_format($enseignant->tx_horaire,0) }} FCFA/h</span>
      @if($enseignant->tel)<span class="profil-item"><strong>Tél :</strong> {{ $enseignant->tel }}</span>@endif
    </div>
  </div>
</div>

{{-- Résumé --}}
<div class="resume-grid">
  <div class="resume-box">
    <div class="resume-val">{{ number_format($volumeTotal,2) }}</div>
    <div class="resume-lbl">Volume total (h)</div>
  </div>
  <div class="resume-box">
    <div class="resume-val">{{ number_format(min($volumeTotal,192),2) }}</div>
    <div class="resume-lbl">Heures normales</div>
  </div>
  <div class="resume-box">
    <div class="resume-val orange">{{ number_format($heuresComplementaires,2) }}</div>
    <div class="resume-lbl">H. complémentaires</div>
  </div>
  <div class="resume-box">
    <div class="resume-val green">{{ number_format($montantTotal + $montantCompl,0,'.',' ') }}</div>
    <div class="resume-lbl">Montant total (FCFA)</div>
  </div>
</div>

{{-- Détail activités --}}
<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Cours</th>
      <th>Séquence</th>
      <th>Type</th>
      <th>Niv.</th>
      <th class="r">Vol. (h)</th>
    </tr>
  </thead>
  <tbody>
    @forelse($activites as $act)
    <tr>
      <td style="white-space:nowrap;color:#6B7A8D;">{{ $act->date_act->format('d/m/Y') }}</td>
      <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
        {{ $act->ressource?->sequence?->cours?->intit ?? '—' }}
      </td>
      <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#6B7A8D;">
        {{ $act->ressource?->sequence?->ttre_seq ?? '—' }}
      </td>
      <td>
        @if($act->id_typ_act == 1)
          <span class="b-green">Création</span>
        @else
          <span class="b-orange">MAJ</span>
        @endif
      </td>
      <td style="text-align:center;font-weight:700;">N{{ $act->ressource?->niv_comp ?? '?' }}</td>
      <td class="r" style="font-weight:700;color:#009962;">{{ number_format($act->v_hor,2) }}</td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;padding:24px;color:#6B7A8D;">Aucune activité enregistrée</td></tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" style="padding:10px 10px;">TOTAL</td>
      <td class="r" style="color:#009962;font-size:14px;">{{ number_format($volumeTotal,2) }}h</td>
    </tr>
  </tfoot>
</table>

{{-- Détail calcul --}}
<div style="background:#F4F6FA;border-radius:8px;padding:14px 18px;font-size:11px;margin-bottom:24px;">
  <div style="font-weight:600;margin-bottom:8px;">Détail du calcul :</div>
  <div style="display:flex;gap:24px;flex-wrap:wrap;">
    <span>Heures normales (≤192h) : {{ number_format(min($volumeTotal,192),2) }}h × {{ number_format($enseignant->tx_horaire,0) }} = <strong>{{ number_format(min($volumeTotal,192)*$enseignant->tx_horaire,0,'.',' ') }} FCFA</strong></span>
    @if($heuresComplementaires > 0)
    <span>H. complémentaires (×150%) : {{ number_format($heuresComplementaires,2) }}h × {{ number_format($enseignant->tx_horaire*1.5,0) }} = <strong>{{ number_format($heuresComplementaires*$enseignant->tx_horaire*1.5,0,'.',' ') }} FCFA</strong></span>
    @endif
    <span style="color:#009962;font-weight:700;">TOTAL : {{ number_format($montantTotal + $montantCompl,0,'.',' ') }} FCFA</span>
  </div>
</div>

{{-- Signatures --}}
<div class="signatures">
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-label">L'Enseignant</div>
    <div class="sig-label" style="margin-top:2px;font-weight:600;">{{ $enseignant->nom_complet }}</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-label">Le Secrétaire Principal</div>
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

<button class="print-btn no-print" onclick="window.print()">
  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
  Imprimer / Enregistrer en PDF
</button>

</body>
</html>
