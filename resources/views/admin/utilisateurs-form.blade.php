@extends('layouts.app')
@section('title', $utilisateur ? 'Modifier compte' : 'Nouveau compte')
@section('sidebar-role','Administrateur')
@section('page-title', $utilisateur ? 'Modifier le compte' : 'Créer un compte')

@section('sidebar-nav')
  <x-nav-item route="admin.dashboard"    label="Tableau de bord"    icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="admin.utilisateurs" label="Utilisateurs"       icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="admin.annees"       label="Années académiques" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
  <x-nav-item route="admin.parametres"    label="Paramètres calcul"  icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
  <x-nav-item route="admin.taux-horaires"  label="Taux horaires"      icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
@endsection

@section('content')
<div style="max-width:520px;">
  <form method="POST" action="{{ $utilisateur ? route('admin.utilisateurs.update',$utilisateur) : route('admin.utilisateurs.store') }}" class="card overflow-hidden">
    @csrf @if($utilisateur) @method('PUT') @endif
    <div class="card-header"><h3>Informations du compte</h3></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

      <div>
        <span class="form-label">Rôle <span style="color:red">*</span></span>
        <div class="role-selector">
          @foreach([['administrateur','⚙️'],['secretaire','📋'],['enseignant','👨‍🏫']] as [$val,$icon])
          <label>
            <input type="radio" name="role" value="{{ $val }}" {{ old('role',$utilisateur?->role??'secretaire')===$val?'checked':'' }}>
            <div class="rs-card">
              <span class="rs-icon">{{ $icon }}</span>
              <span class="rs-name">{{ ucfirst($val) }}</span>
            </div>
          </label>
          @endforeach
        </div>
        @error('role')<div class="form-error">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="form-label">Login <span style="color:red">*</span></label>
        <input type="text" name="login" value="{{ old('login',$utilisateur?->login) }}" class="form-input" required>
        @error('login')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="form-label">Email <span style="color:red">*</span></label>
        <input type="email" name="email" value="{{ old('email',$utilisateur?->email) }}" class="form-input" required>
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="form-label">Mot de passe {{ $utilisateur ? '(laisser vide pour ne pas changer)' : '*' }}</label>
        <input type="password" name="password" class="form-input" {{ $utilisateur?'':'required' }} minlength="8">
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="form-label">Confirmer mot de passe</label>
        <input type="password" name="password_confirmation" class="form-input">
      </div>
    </div>
    <div class="card-footer">
      <a href="{{ route('admin.utilisateurs') }}" style="font-size:13px;color:var(--muted);text-decoration:none;">Annuler</a>
      <button type="submit" class="btn btn-navy">{{ $utilisateur ? 'Enregistrer' : 'Créer le compte' }}</button>
    </div>
  </form>
</div>
@endsection
