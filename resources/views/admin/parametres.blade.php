@extends('layouts.app')
@section('title','Paramètres de calcul')
@section('sidebar-role','Administrateur')
@section('page-title','Paramètres de calcul')
@section('page-subtitle','Définissez les coefficients utilisés pour calculer les volumes horaires')

@section('sidebar-nav')
  <x-nav-item route="admin.dashboard"    label="Tableau de bord"    icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="admin.utilisateurs" label="Utilisateurs"       icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="admin.annees"       label="Années académiques" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
  <x-nav-item route="admin.parametres"   label="Paramètres calcul"  icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
  <x-nav-item route="admin.taux-horaires" label="Taux horaires"     icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
@endsection

@section('content')
<div style="max-width:700px;display:flex;flex-direction:column;gap:20px;">

  {{-- Info formule --}}
  <div style="background:var(--green-light);border:1px solid rgba(0,192,127,.3);border-radius:12px;padding:16px 20px;display:flex;gap:12px;">
    <span style="font-size:20px;flex-shrink:0;">📐</span>
    <div>
      <div style="font-weight:600;font-size:13px;color:var(--green-dark);margin-bottom:4px;">Formule de calcul</div>
      <div style="font-size:12px;color:var(--muted);line-height:1.6;">
        <strong style="color:var(--navy);">v_hor = coefficient(type, niveau) × nombre_séquences</strong><br>
        Le <em>type</em> est soit "Création" (1) soit "Mise à jour" (2). Le <em>niveau</em> est la complexité de la ressource (1, 2 ou 3).
      </div>
    </div>
  </div>

  {{-- Formulaire --}}
  <form method="POST" action="{{ route('admin.parametres.update') }}" class="card">
    @csrf @method('PUT')

    <div class="card-header">
      <h3>Coefficients par type et niveau</h3>
    </div>

    <div class="card-body" style="display:flex;flex-direction:column;gap:24px;">

      {{-- Création --}}
      <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
          <span class="badge-green" style="font-size:12px;padding:4px 12px;">Création de ressource (Type 1)</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
          @foreach([1=>['label'=>'Niveau 1','desc'=>'Contenus simples + quiz'],2=>['label'=>'Niveau 2','desc'=>'Niv.1 + activités interactives'],3=>['label'=>'Niveau 3','desc'=>'Serious games, simulations']] as $niv=>$info)
          <div>
            <label class="form-label">{{ $info['label'] }}</label>
            <input type="number" name="creation_niv{{ $niv }}"
              value="{{ old('creation_niv'.$niv, $coefficients[1][$niv] ?? '') }}"
              class="form-input" step="0.001" min="0" max="10" required>
            <div style="font-size:11px;color:var(--muted);margin-top:4px;">{{ $info['desc'] }}</div>
          </div>
          @endforeach
        </div>
        <div style="margin-top:10px;font-size:11px;color:var(--muted);background:#F4F6FA;padding:8px 12px;border-radius:8px;">
          Exemple Niveau 1 : {{ $coefficients[1][1] ?? 0.40 }} × 40 séquences = <strong>{{ round(($coefficients[1][1]??0.40)*40,1) }}h</strong>
        </div>
      </div>

      {{-- Mise à jour --}}
      <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
          <span class="badge-orange" style="font-size:12px;padding:4px 12px;">Mise à jour de ressource (Type 2)</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
          @foreach([1=>['label'=>'Niveau 1','desc'=>'50% du coefficient création'],2=>['label'=>'Niveau 2','desc'=>'50% du coefficient création'],3=>['label'=>'Niveau 3','desc'=>'50% du coefficient création']] as $niv=>$info)
          <div>
            <label class="form-label">{{ $info['label'] }}</label>
            <input type="number" name="maj_niv{{ $niv }}"
              value="{{ old('maj_niv'.$niv, $coefficients[2][$niv] ?? '') }}"
              class="form-input" step="0.001" min="0" max="10" required>
            <div style="font-size:11px;color:var(--muted);margin-top:4px;">{{ $info['desc'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Seuil heures complémentaires --}}
      <div style="border-top:1px solid var(--border);padding-top:20px;">
        <label class="form-label">Seuil heures complémentaires (h) <span style="color:red">*</span></label>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
          <input type="number" name="seuil" value="{{ old('seuil',$seuil) }}"
            class="form-input" style="max-width:180px;" min="1" max="5000" required>
          <span style="font-size:12px;color:var(--muted);">Au-delà de ce seuil, les heures sont considérées complémentaires et majorées à 150%.</span>
        </div>
        @error('seuil')<div class="form-error">{{ $message }}</div>@enderror
      </div>

    </div>

    <div class="card-footer">
      <span style="font-size:12px;color:var(--muted);">Les modifications s'appliquent aux nouvelles activités enregistrées.</span>
      <button type="submit" class="btn btn-navy">Enregistrer les paramètres</button>
    </div>
  </form>

</div>
@endsection
