@extends('layouts.app')
@section('title','Taux horaires')
@section('sidebar-role','Administrateur')
@section('page-title','Gestion des taux horaires')
@section('page-subtitle','Modifiez le taux horaire de chaque enseignant')

@section('sidebar-nav')
  <x-nav-item route="admin.dashboard"     label="Tableau de bord"    icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="admin.utilisateurs"  label="Utilisateurs"       icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="admin.annees"        label="Années académiques" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
  <x-nav-item route="admin.parametres"    label="Paramètres calcul"  icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
  <x-nav-item route="admin.taux-horaires" label="Taux horaires"      icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3>{{ $enseignants->count() }} enseignant(s)</h3>
    <span style="font-size:12px;color:var(--muted);">Cliquez sur le taux pour le modifier directement</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Enseignant</th>
          <th class="hide-mobile">Grade</th>
          <th class="hide-mobile">Statut</th>
          <th style="text-align:right;">Taux actuel (FCFA/h)</th>
          <th style="text-align:right;">Nouveau taux</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @php $avc=['av-green','av-blue','av-purple','av-orange','av-teal']; @endphp
        @forelse($enseignants as $idx => $ens)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar {{ $avc[$idx % 5] }}">{{ $ens->initiales }}</div>
              <div>
                <div style="font-weight:500;font-size:13.5px;">{{ $ens->nom_complet }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $ens->departement?->lib_dep }}</div>
              </div>
            </div>
          </td>
          <td class="hide-mobile"><span class="badge-green">{{ $ens->grade?->lib_grd }}</span></td>
          <td class="hide-mobile">
            @if(strtolower($ens->statut?->lib_stat??'') === 'permanent')
              <span class="badge-blue">Permanent</span>
            @else
              <span class="badge-orange">Vacataire</span>
            @endif
          </td>
          <td style="text-align:right;font-weight:700;font-size:15px;color:var(--green-dark);">
            {{ number_format($ens->tx_horaire, 0, '.', ' ') }}
          </td>
          <td style="text-align:right;">
            <form method="POST"
              action="{{ route('admin.taux-horaires.update', $ens) }}"
              style="display:flex;align-items:center;gap:8px;justify-content:flex-end;">
              @csrf @method('PUT')
              <input type="number" name="tx_horaire"
                value="{{ $ens->tx_horaire }}"
                min="0" step="100"
                style="width:110px;padding:7px 10px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;text-align:right;font-family:inherit;outline:none;color:var(--navy);"
                onfocus="this.style.borderColor='#00C07F'" onblur="this.style.borderColor='var(--border)'">
          </td>
          <td>
              <button type="submit" class="btn btn-green btn-sm">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Valider
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">Aucun enseignant</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
