@extends('layouts.app')
@section('title','Logs de connexion')
@section('sidebar-role','Administrateur')
@section('page-title','Logs de connexion')
@section('page-subtitle','Historique des connexions et déconnexions')

@section('sidebar-nav')
  <x-nav-item route="admin.dashboard"    label="Tableau de bord"    icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="admin.utilisateurs" label="Utilisateurs"       icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="admin.annees"       label="Années académiques" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
  <x-nav-item route="admin.parametres"   label="Paramètres calcul"  icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
  <x-nav-item route="admin.taux-horaires" label="Taux horaires"     icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
  <x-nav-item route="admin.logs"         label="Logs de connexion"  icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
@endsection

@section('topbar-actions')
  {{-- Filtre --}}
  <form method="GET" action="{{ route('admin.logs') }}" style="display:flex;gap:8px;flex-wrap:wrap;">
    <select name="action" onchange="this.form.submit()"
      style="padding:7px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#fff;outline:none;cursor:pointer;font-family:inherit;color:var(--navy);">
      <option value="">Toutes actions</option>
      <option value="connexion"     {{ request('action')==='connexion'?'selected':'' }}>Connexions</option>
      <option value="deconnexion"   {{ request('action')==='deconnexion'?'selected':'' }}>Déconnexions</option>
    </select>
    <select name="statut" onchange="this.form.submit()"
      style="padding:7px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;background:#fff;outline:none;cursor:pointer;font-family:inherit;color:var(--navy);">
      <option value="">Tous statuts</option>
      <option value="succes" {{ request('statut')==='succes'?'selected':'' }}>Succès</option>
      <option value="echec"  {{ request('statut')==='echec'?'selected':'' }}>Échecs</option>
    </select>
  </form>
@endsection

@section('content')

{{-- KPI --}}
<div class="kpi-grid" style="margin-bottom:20px;">
  <div class="kpi-card">
    <div class="kpi-icon">✅</div>
    <div class="kpi-value">{{ $nbSucces }}</div>
    <div class="kpi-label">Connexions réussies</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#00C07F;width:{{ $nbTotal>0?round($nbSucces/$nbTotal*100):0 }}%"></div></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon">❌</div>
    <div class="kpi-value">{{ $nbEchecs }}</div>
    <div class="kpi-label">Tentatives échouées</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#FF6B35;width:{{ $nbTotal>0?round($nbEchecs/$nbTotal*100):0 }}%"></div></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon">👥</div>
    <div class="kpi-value">{{ $nbUtilisateursActifs }}</div>
    <div class="kpi-label">Utilisateurs actifs (30j)</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#4A90E2;width:70%"></div></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon">📋</div>
    <div class="kpi-value">{{ $nbTotal }}</div>
    <div class="kpi-label">Total événements</div>
    <div class="kpi-bar"><div class="kpi-fill" style="background:#9B59B6;width:60%"></div></div>
  </div>
</div>

{{-- Table logs --}}
<div class="card">
  <div class="card-header">
    <h3>Historique des connexions</h3>
    <span style="font-size:12px;color:var(--muted);">{{ $logs->total() }} événement(s)</span>
  </div>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;min-width:600px;">
      <thead>
        <tr style="background:#FAFBFC;">
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Date / Heure</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Utilisateur</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Rôle</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Action</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">Statut</th>
          <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;" class="hide-mobile">Adresse IP</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
        <tr style="border-top:1px solid #F0F2F5;{{ $log->statut==='echec' ? 'background:#FFF8F5;' : '' }}">
          <td style="padding:11px 16px;">
            <div style="font-size:13px;font-weight:500;color:var(--navy);">{{ $log->created_at->format('d/m/Y') }}</div>
            <div style="font-size:11px;color:var(--muted);">{{ $log->created_at->format('H:i:s') }}</div>
          </td>
          <td style="padding:11px 16px;">
            @php
              $roles = ['administrateur'=>'av-green','secretaire'=>'av-blue','enseignant'=>'av-purple'];
              $cls   = $roles[$log->utilisateur?->role ?? ''] ?? 'av-teal';
              $init  = strtoupper(substr($log->utilisateur?->login ?? '?', 0, 2));
            @endphp
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar {{ $cls }}" style="width:32px;height:32px;font-size:11px;">{{ $init }}</div>
              <div>
                <div style="font-size:13px;font-weight:500;">{{ $log->utilisateur?->login ?? 'Compte supprimé' }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $log->utilisateur?->email ?? '—' }}</div>
              </div>
            </div>
          </td>
          <td style="padding:11px 16px;">
            @php $badgeCls = ['administrateur'=>'badge-green','secretaire'=>'badge-blue','enseignant'=>'badge-purple']; @endphp
            @if($log->utilisateur)
              <span class="{{ $badgeCls[$log->utilisateur->role] ?? 'badge-gray' }}">
                {{ ucfirst($log->utilisateur->role) }}
              </span>
            @else
              <span class="badge-gray">—</span>
            @endif
          </td>
          <td style="padding:11px 16px;">
            @if($log->action === 'connexion')
              <div style="display:flex;align-items:center;gap:6px;">
                <svg width="14" height="14" fill="none" stroke="#00C07F" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <span style="font-size:13px;color:#009962;font-weight:500;">Connexion</span>
              </div>
            @else
              <div style="display:flex;align-items:center;gap:6px;">
                <svg width="14" height="14" fill="none" stroke="#6B7A8D" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span style="font-size:13px;color:var(--muted);font-weight:500;">Déconnexion</span>
              </div>
            @endif
          </td>
          <td style="padding:11px 16px;">
            @if($log->statut === 'succes')
              <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#E6FBF3;color:#009962;">
                <span style="width:6px;height:6px;border-radius:50%;background:#00C07F;display:inline-block;"></span>
                Succès
              </span>
            @else
              <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#FFF0EB;color:#FF6B35;">
                <span style="width:6px;height:6px;border-radius:50%;background:#FF6B35;display:inline-block;"></span>
                Échec
              </span>
            @endif
          </td>
          <td style="padding:11px 16px;font-size:12px;color:var(--muted);font-family:monospace;" class="hide-mobile">
            {{ $log->ip ?? '—' }}
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="padding:40px;text-align:center;color:var(--muted);">Aucun log enregistré</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($logs->hasPages())
  <div style="padding:14px 16px;border-top:1px solid var(--border);">{{ $logs->links() }}</div>
  @endif
</div>
@endsection
