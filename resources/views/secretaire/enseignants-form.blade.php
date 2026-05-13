@extends('layouts.app')
@section('title', $enseignant ? 'Modifier enseignant' : 'Ajouter un enseignant')
@section('sidebar-role','Secrétaire Principal')
@section('page-title', $enseignant ? 'Modifier l\'enseignant' : 'Ajouter un enseignant')
@section('page-subtitle', $enseignant ? $enseignant->nom_complet : 'Remplissez les informations ci-dessous')

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('content')
<div style="max-width:580px;">
  <form method="POST"
    action="{{ $enseignant ? route('secretaire.enseignants.update',$enseignant) : route('secretaire.enseignants.store') }}"
    class="card">
    @csrf
    @if($enseignant) @method('PUT') @endif

    <div class="card-header">
      <h3>{{ $enseignant ? 'Modifier les informations' : 'Nouvel enseignant' }}</h3>
    </div>

    <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

      {{-- Nom / Prénom --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
          <label class="form-label">Prénom <span style="color:red">*</span></label>
          <input type="text" name="pnom" value="{{ old('pnom',$enseignant?->pnom) }}"
            class="form-input" placeholder="ex: Kouamé" required>
          @error('pnom')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="form-label">Nom <span style="color:red">*</span></label>
          <input type="text" name="nom" value="{{ old('nom',$enseignant?->nom) }}"
            class="form-input" placeholder="ex: KOFFI" required>
          @error('nom')<div class="form-error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Téléphone --}}
      <div>
        <label class="form-label">Téléphone</label>
        <input type="text" name="tel" value="{{ old('tel',$enseignant?->tel) }}"
          class="form-input" placeholder="ex: 0700000000">
      </div>

      {{-- Taux horaire --}}
      <div>
        <label class="form-label">Taux horaire (FCFA/h) <span style="color:red">*</span></label>
        <input type="number" name="tx_horaire" value="{{ old('tx_horaire',$enseignant?->tx_horaire ?? 5000) }}"
          class="form-input" min="0" step="100" required>
        @error('tx_horaire')<div class="form-error">{{ $message }}</div>@enderror
      </div>

      {{-- Grade / Statut / Département --}}
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
        <div>
          <label class="form-label">Grade <span style="color:red">*</span></label>
          <select name="id_grd" class="form-input" required>
            <option value="">Choisir…</option>
            @foreach($grades as $g)
            <option value="{{ $g->id_grd }}" {{ old('id_grd',$enseignant?->id_grd)==$g->id_grd?'selected':'' }}>
              {{ $g->lib_grd }}
            </option>
            @endforeach
          </select>
          @error('id_grd')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="form-label">Statut <span style="color:red">*</span></label>
          <select name="id_stat" class="form-input" required>
            <option value="">Choisir…</option>
            @foreach($statuts as $s)
            <option value="{{ $s->id_stat }}" {{ old('id_stat',$enseignant?->id_stat)==$s->id_stat?'selected':'' }}>
              {{ $s->lib_stat }}
            </option>
            @endforeach
          </select>
          @error('id_stat')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="form-label">Département <span style="color:red">*</span></label>
          <select name="id_dep" class="form-input" required>
            <option value="">Choisir…</option>
            @foreach($departements as $d)
            <option value="{{ $d->id_dep }}" {{ old('id_dep',$enseignant?->id_dep)==$d->id_dep?'selected':'' }}>
              {{ $d->lib_dep }}
            </option>
            @endforeach
          </select>
          @error('id_dep')<div class="form-error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Compte utilisateur (optionnel) --}}
      @if(!$enseignant)
      <div style="background:#F4F6FA;border-radius:10px;padding:16px;border:1px solid var(--border);">
        <div style="font-size:13px;font-weight:600;color:var(--navy);margin-bottom:12px;">
          🔐 Créer un compte d'accès (optionnel)
        </div>
        <div style="display:flex;flex-direction:column;gap:12px;">
          <div>
            <label class="form-label">Email de connexion</label>
            <input type="email" name="email_compte" value="{{ old('email_compte') }}"
              class="form-input" placeholder="enseignant@uvci.ci">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div>
              <label class="form-label">Mot de passe</label>
              <input type="password" name="password_compte" class="form-input" minlength="8">
            </div>
            <div>
              <label class="form-label">Confirmer</label>
              <input type="password" name="password_compte_confirmation" class="form-input">
            </div>
          </div>
        </div>
      </div>
      @endif

    </div>

    <div class="card-footer">
      <a href="{{ route('secretaire.enseignants') }}" style="font-size:13px;color:var(--muted);text-decoration:none;">
        ← Annuler
      </a>
      <button type="submit" class="btn btn-navy">
        {{ $enseignant ? 'Enregistrer les modifications' : 'Ajouter l\'enseignant' }}
      </button>
    </div>
  </form>
</div>
@endsection
