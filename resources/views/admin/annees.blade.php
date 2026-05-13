@extends('layouts.app')
@section('title','Années académiques')
@section('sidebar-role','Administrateur')
@section('page-title','Années académiques')

@section('sidebar-nav')
  <x-nav-item route="admin.dashboard"    label="Tableau de bord"    icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="admin.utilisateurs" label="Utilisateurs"       icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="admin.annees"       label="Années académiques" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
  <x-nav-item route="admin.parametres"    label="Paramètres calcul"  icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
  <x-nav-item route="admin.taux-horaires"  label="Taux horaires"      icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
  <div class="card">
    <div class="card-header"><h3>Années enregistrées</h3></div>
    @php $bdg=['en_cours'=>'badge-green','a_venir'=>'badge-blue','cloturee'=>'badge-gray']; $lbl=['en_cours'=>'En cours','a_venir'=>'À venir','cloturee'=>'Clôturée']; @endphp
    @forelse($annees as $a)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #F0F2F5;gap:12px;">
      <div>
        <div style="font-weight:700;font-size:15px;">{{ $a->lib_anee }}</div>
        <div style="font-size:12px;color:var(--muted);margin-top:2px;">{{ \Carbon\Carbon::parse($a->dte_dbut)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($a->dte_fn)->format('d/m/Y') }}</div>
      </div>
      <span class="{{ $bdg[$a->etat_anee]??'badge-gray' }}">{{ $lbl[$a->etat_anee]??$a->etat_anee }}</span>
    </div>
    @empty
    <div style="padding:32px;text-align:center;font-size:13px;color:var(--muted);">Aucune année configurée</div>
    @endforelse
  </div>

  <div class="card">
    <div class="card-header"><h3>Ajouter une année</h3></div>
    <form method="POST" action="{{ route('admin.annees.store') }}" class="card-body" style="display:flex;flex-direction:column;gap:14px;">
      @csrf
      <div>
        <label class="form-label">Libellé <span style="color:red">*</span></label>
        <input type="text" name="lib_anee" value="{{ old('lib_anee') }}" placeholder="ex: 2025-2026" class="form-input" required>
        @error('lib_anee')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div class="grid-2">
        <div>
          <label class="form-label">Date début <span style="color:red">*</span></label>
          <input type="date" name="dte_dbut" value="{{ old('dte_dbut') }}" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Date fin <span style="color:red">*</span></label>
          <input type="date" name="dte_fn" value="{{ old('dte_fn') }}" class="form-input" required>
        </div>
      </div>
      <div>
        <label class="form-label">État <span style="color:red">*</span></label>
        <select name="etat_anee" class="form-input" required>
          <option value="a_venir"  {{ old('etat_anee')==='a_venir'?'selected':'' }}>À venir</option>
          <option value="en_cours" {{ old('etat_anee')==='en_cours'?'selected':'' }}>En cours</option>
          <option value="cloturee" {{ old('etat_anee')==='cloturee'?'selected':'' }}>Clôturée</option>
        </select>
      </div>
      <button type="submit" class="btn btn-navy" style="justify-content:center;">Créer l'année</button>
    </form>
  </div>
</div>
@endsection
