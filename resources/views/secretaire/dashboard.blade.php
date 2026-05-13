@extends('layouts.app')
@section('title','Secrétaire')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','Tableau de bord')
@section('page-subtitle','Gestion des enseignants et activités pédagogiques')

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('topbar-actions')
  <a href="{{ route('secretaire.enseignants.create') }}" class="btn btn-navy">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    <span class="btn-text">Ajouter enseignant</span>
  </a>
  <a href="{{ route('secretaire.activites.create') }}" class="btn btn-green">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    <span class="btn-text">Nouvelle activité</span>
  </a>
@endsection

@section('content')

{{-- KPI --}}
<div class="kpi-grid">
@php $kpis=[
  ['label'=>'Enseignants','value'=>$stats['total_enseignants'],'icon'=>'👨‍🏫','color'=>'#00C07F','pct'=>80],
  ['label'=>'Cours','value'=>$stats['total_cours'],'icon'=>'📚','color'=>'#4A90E2','pct'=>65],
  ['label'=>'Activités','value'=>$stats['total_activites'],'icon'=>'📋','color'=>'#FF6B35','pct'=>70],
  ['label'=>'Volume total (h)','value'=>number_format($stats['volume_total'],1),'icon'=>'⏱️','color'=>'#9B59B6','pct'=>55],
]; @endphp
@foreach($kpis as $k)
<div class="kpi-card">
  <div class="kpi-icon">{{ $k['icon'] }}</div>
  <div class="kpi-value">{{ $k['value'] }}</div>
  <div class="kpi-label">{{ $k['label'] }}</div>
  <div class="kpi-bar"><div class="kpi-fill" style="background:{{ $k['color'] }};width:{{ $k['pct'] }}%"></div></div>
</div>
@endforeach
</div>

{{-- TABLE ENSEIGNANTS --}}
<div class="card">
  <div class="card-header">
    <h3>Enseignants — {{ $annee?->lib_anee ?? 'Aucune année active' }}</h3>
    <a href="{{ route('secretaire.enseignants.create') }}" class="btn btn-green btn-sm">+ Ajouter</a>
  </div>

  <div style="padding:12px 20px;border-bottom:1px solid var(--border);display:flex;gap:10px;flex-wrap:wrap;">
    <div style="position:relative;flex:1;min-width:180px;">
      <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--muted);pointer-events:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" oninput="filterTable()" placeholder="Rechercher…" style="width:100%;padding:8px 12px 8px 34px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#FAFAFA;outline:none;font-family:inherit;color:var(--navy);" onfocus="this.style.borderColor='#00C07F'" onblur="this.style.borderColor='var(--border)'">
    </div>
    <select id="filterStatut" onchange="filterTable()" style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#FAFAFA;outline:none;cursor:pointer;font-family:inherit;color:var(--navy);">
      <option value="">Tous les statuts</option>
      <option value="permanent">Permanent</option>
      <option value="vacataire">Vacataire</option>
    </select>
  </div>

  <div class="table-wrap">
    <table id="ensTable">
      <thead>
        <tr>
          <th>Enseignant</th>
          <th>Grade</th>
          <th class="hide-mobile">Statut</th>
          <th class="hide-mobile">Département</th>
          <th>Volume (h)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @php $avc=['av-green','av-blue','av-purple','av-orange','av-teal']; @endphp
        @forelse($enseignants as $ens)
        <tr data-statut="{{ strtolower($ens->statut?->lib_stat ?? '') }}" data-nom="{{ strtolower($ens->nom_complet) }}">
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar {{ $avc[$loop->index % 5] }}">{{ $ens->initiales }}</div>
              <div>
                <div style="font-weight:500;font-size:13.5px;">{{ $ens->nom_complet }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $ens->utilisateur?->email ?? '—' }}</div>
              </div>
            </div>
          </td>
          <td><span class="badge-green">{{ $ens->grade?->lib_grd ?? '—' }}</span></td>
          <td class="hide-mobile">
            @if(strtolower($ens->statut?->lib_stat ?? '') === 'permanent')
              <span class="badge-blue">Permanent</span>
            @else
              <span class="badge-orange">Vacataire</span>
            @endif
          </td>
          <td class="hide-mobile" style="color:var(--muted);font-size:13px;">{{ $ens->departement?->lib_dep ?? '—' }}</td>
          <td style="font-weight:700;font-size:14px;">{{ number_format($ens->volume_horaire ?? 0,1) }}h</td>
          <td>
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
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">Aucun enseignant enregistré</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($enseignants->hasPages())
  <div style="padding:14px 20px;border-top:1px solid var(--border);">{{ $enseignants->links() }}</div>
  @endif
</div>

@push('scripts')
<script>
function filterTable(){
  var s=document.getElementById('searchInput').value.toLowerCase();
  var st=document.getElementById('filterStatut').value.toLowerCase();
  document.querySelectorAll('#ensTable tbody tr[data-nom]').forEach(function(r){
    var n=r.dataset.nom||'';var t=r.dataset.statut||'';
    r.style.display=(!s||n.includes(s))&&(!st||t.includes(st))?'':'none';
  });
}
</script>
@endpush
@endsection
