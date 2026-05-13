@extends('layouts.app')
@section('title','Nouvelle activité')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','Enregistrer une activité')
@section('page-subtitle','Le volume horaire est calculé automatiquement')

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('content')
<div style="max-width:600px;">
  <form method="POST" action="{{ route('secretaire.activites.store') }}" class="card">
    @csrf

    <div class="card-header">
      <h3>Détails de l'activité pédagogique</h3>
    </div>

    <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

      {{-- Aperçu calcul --}}
      <div id="calcPreview" style="display:none;background:var(--green-light);border:1px solid rgba(0,192,127,.3);border-radius:10px;padding:12px 16px;">
        <div style="font-size:12px;color:var(--green-dark);font-weight:500;margin-bottom:2px;">Volume horaire estimé</div>
        <div style="font-size:22px;font-weight:700;color:var(--green-dark);" id="calcResult">—</div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px;">Calculé automatiquement selon le niveau et le nombre de séquences</div>
      </div>

      {{-- Date + Année --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
          <label class="form-label">Date de l'activité <span style="color:red">*</span></label>
          <input type="date" name="date_act" value="{{ old('date_act', date('Y-m-d')) }}" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Année académique <span style="color:red">*</span></label>
          <select name="id_anee" class="form-input" required>
            <option value="">Choisir…</option>
            @foreach($annees as $a)
            <option value="{{ $a->id_anee }}" {{ $a->etat_anee==='en_cours'?'selected':'' }}>
              {{ $a->lib_anee }}{{ $a->etat_anee==='en_cours' ? ' (en cours)' : '' }}
            </option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Enseignant --}}
      <div>
        <label class="form-label">Enseignant <span style="color:red">*</span></label>
        <select name="id_ens" class="form-input" required>
          <option value="">Choisir un enseignant…</option>
          @foreach($enseignants as $ens)
          <option value="{{ $ens->id_ens }}" {{ old('id_ens')==$ens->id_ens?'selected':'' }}>
            {{ $ens->nom_complet }} — {{ $ens->grade?->lib_grd }}
          </option>
          @endforeach
        </select>
        @error('id_ens')<div class="form-error">{{ $message }}</div>@enderror
      </div>

      {{-- Type d'activité --}}
      <div>
        <label class="form-label">Type d'activité <span style="color:red">*</span></label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
          @foreach($typesActivites as $type)
          <label style="cursor:pointer;">
            <input type="radio" name="id_typ_act" value="{{ $type->id_typ_act }}"
              onchange="updateCalc()"
              {{ old('id_typ_act',1)==$type->id_typ_act?'checked':'' }}
              style="display:none;" class="type-radio">
            <div class="type-card" data-val="{{ $type->id_typ_act }}"
              style="padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:#FAFAFA;transition:all .2s;display:flex;align-items:center;gap:8px;">
              <div style="width:16px;height:16px;border-radius:50%;border:2px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;" class="radio-dot"></div>
              <span style="font-size:13.5px;font-weight:500;color:var(--navy);">{{ $type->lib_typ_act }}</span>
            </div>
          </label>
          @endforeach
        </div>
        @error('id_typ_act')<div class="form-error">{{ $message }}</div>@enderror
      </div>

      {{-- Ressource --}}
      <div>
        <label class="form-label">Ressource pédagogique <span style="color:red">*</span></label>
        <select name="id_ress" id="selectRess" onchange="updateCalc()" class="form-input">
          <option value="">Choisir une ressource…</option>
          @foreach($ressources as $r)
          <option value="{{ $r->id_ress }}"
            data-niv="{{ $r->niv_comp }}"
            data-nbseq="{{ $r->nb_sequences }}"
            {{ old('id_ress')==$r->id_ress?'selected':'' }}>
            {{ $r->sequence?->cours?->intit ?? '?' }} › {{ $r->sequence?->ttre_seq }} (Niveau {{ $r->niv_comp }})
          </option>
          @endforeach
        </select>
      </div>

      {{-- Observation --}}
      <div>
        <label class="form-label">Observation</label>
        <textarea name="observation" rows="3" class="form-input" style="resize:vertical;"
          placeholder="Remarques éventuelles…">{{ old('observation') }}</textarea>
      </div>

    </div>

    <div class="card-footer">
      <a href="{{ route('secretaire.activites') }}" style="font-size:13px;color:var(--muted);text-decoration:none;">← Annuler</a>
      <button type="submit" class="btn btn-navy">Enregistrer l'activité</button>
    </div>
  </form>
</div>

@push('scripts')
<script>
var coeffs = {1:{1:0.40,2:0.75,3:1.50}, 2:{1:0.20,2:0.375,3:0.75}};

function updateCalc() {
  var sel    = document.getElementById('selectRess');
  var opt    = sel.options[sel.selectedIndex];
  var niv    = parseInt(opt ? opt.getAttribute('data-niv') : 0) || 0;
  var nbSeq  = parseInt(opt ? opt.getAttribute('data-nbseq') : 0) || 0;
  var typeEl = document.querySelector('.type-radio:checked');
  var typeId = typeEl ? parseInt(typeEl.value) : 1;
  var prev   = document.getElementById('calcPreview');
  var res    = document.getElementById('calcResult');

  // Mise à jour style boutons radio
  document.querySelectorAll('.type-radio').forEach(function(r) {
    var card = r.nextElementSibling;
    var dot  = card ? card.querySelector('.radio-dot') : null;
    if (r.checked) {
      card.style.borderColor = '#00C07F';
      card.style.background  = '#E6FBF3';
      if (dot) { dot.style.borderColor = '#00C07F'; dot.style.background = '#00C07F'; }
    } else {
      card.style.borderColor = 'var(--border)';
      card.style.background  = '#FAFAFA';
      if (dot) { dot.style.borderColor = 'var(--border)'; dot.style.background = 'transparent'; }
    }
  });

  if (niv && nbSeq && typeId && coeffs[typeId] && coeffs[typeId][niv]) {
    var vhor = (coeffs[typeId][niv] * nbSeq).toFixed(2);
    res.textContent = vhor + 'h';
    prev.style.display = 'block';
  } else {
    prev.style.display = 'none';
  }
}

// Init au chargement
document.addEventListener('DOMContentLoaded', updateCalc);
</script>
@endpush
@endsection
