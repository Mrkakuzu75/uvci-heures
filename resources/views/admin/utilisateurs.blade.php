@extends('layouts.app')
@section('title','Utilisateurs')
@section('sidebar-role','Administrateur')
@section('page-title','Utilisateurs')
@section('page-subtitle','Gestion des comptes d\'accès')

@section('sidebar-nav')
  <x-nav-item route="admin.dashboard"    label="Tableau de bord"    icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="admin.utilisateurs" label="Utilisateurs"       icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="admin.annees"       label="Années académiques" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
  <x-nav-item route="admin.parametres"    label="Paramètres calcul"  icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
  <x-nav-item route="admin.taux-horaires"  label="Taux horaires"      icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
@endsection

@section('topbar-actions')
  <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-navy">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    <span class="btn-text">Nouveau</span>
  </a>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3>{{ $utilisateurs->total() }} compte(s)</h3>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Utilisateur</th>
          <th>Rôle</th>
          <th class="hide-mobile">Créé le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @php $avCls=['administrateur'=>'av-green','secretaire'=>'av-blue','enseignant'=>'av-purple']; @endphp
        @php $bdgCls=['administrateur'=>'badge-green','secretaire'=>'badge-blue','enseignant'=>'badge-purple']; @endphp
        @forelse($utilisateurs as $u)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar {{ $avCls[$u->role]??'av-teal' }}">{{ strtoupper(substr($u->login,0,2)) }}</div>
              <div>
                <div style="font-weight:500;">{{ $u->login }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $u->email }}</div>
              </div>
            </div>
          </td>
          <td><span class="{{ $bdgCls[$u->role]??'badge-gray' }}">{{ $u->role }}</span></td>
          <td class="hide-mobile" style="color:var(--muted);">{{ $u->created_at->format('d/m/Y') }}</td>
          <td>
            <div style="display:flex;gap:6px;">
              <a href="{{ route('admin.utilisateurs.edit',$u) }}" class="btn btn-outline btn-sm">Modifier</a>
              <form method="POST" action="{{ route('admin.utilisateurs.destroy',$u) }}" onsubmit="return confirm('Supprimer ce compte ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Suppr.</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted);">Aucun utilisateur</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($utilisateurs->hasPages())
  <div style="padding:14px 20px;border-top:1px solid var(--border);">{{ $utilisateurs->links() }}</div>
  @endif
</div>
@endsection
