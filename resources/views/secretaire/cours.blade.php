@extends('layouts.app')
@section('title','Cours')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','Cours')
@section('page-subtitle','Gestion des cours et ressources pédagogiques')

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr;gap:20px;">
  @media screen and (min-width:900px){ grid-template-columns: 1fr 380px; }

  {{-- Liste des cours --}}
  <div class="card">
    <div class="card-header">
      <h3>{{ $cours->total() }} cours enregistré(s)</h3>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Cours</th>
            <th>Niveau</th>
            <th class="hide-mobile">Séquences</th>
            <th>Crédits</th>
          </tr>
        </thead>
        <tbody>
          @forelse($cours as $c)
          <tr>
            <td>
              <div style="font-weight:500;font-size:13.5px;">{{ $c->intit }}</div>
              <div style="font-size:11px;color:var(--muted);">{{ $c->filre }} — {{ $c->semestre?->lib_sem }}</div>
            </td>
            <td><span class="badge-blue">{{ $c->niv }}</span></td>
            <td class="hide-mobile" style="color:var(--muted);font-size:13px;">{{ $c->nbr_squce }}</td>
            <td style="font-weight:700;">{{ $c->nbr_crdt }} Cr</td>
          <td>
            <a href="{{ route('secretaire.sequences', $c) }}"
               style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:500;background:var(--green-light);color:var(--green-dark);text-decoration:none;border:1px solid rgba(0,192,127,.3);">
              Séquences →
            </a>
          </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted);">Aucun cours enregistré</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($cours->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border);">{{ $cours->links() }}</div>
    @endif
  </div>

</div>

{{-- Formulaire sous le tableau --}}
<div class="card" style="margin-top:20px;">
  <div class="card-header">
    <h3>Ajouter un cours</h3>
  </div>
  <form method="POST" action="{{ route('secretaire.cours.store') }}" class="card-body">
    @csrf
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">

      <div>
        <label class="form-label">Intitulé <span style="color:red">*</span></label>
        <input type="text" name="intit" value="{{ old('intit') }}" class="form-input" placeholder="ex: Introduction à l'informatique" required>
        @error('intit')<div class="form-error">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="form-label">Filière <span style="color:red">*</span></label>
        <input type="text" name="filre" value="{{ old('filre') }}" class="form-input" placeholder="ex: Informatique" required>
        @error('filre')<div class="form-error">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="form-label">Niveau <span style="color:red">*</span></label>
        <select name="niv" class="form-input" required>
          @foreach(['L1','L2','L3','M1','M2'] as $n)
          <option value="{{ $n }}" {{ old('niv')===$n?'selected':'' }}>{{ $n }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="form-label">Crédits <span style="color:red">*</span></label>
        <input type="number" name="nbr_crdt" value="{{ old('nbr_crdt',1) }}" class="form-input" min="1" required>
      </div>

      <div>
        <label class="form-label">Heures de base <span style="color:red">*</span></label>
        <input type="number" name="nbh_bse" value="{{ old('nbh_bse',10) }}" class="form-input" min="1" step="0.5" required>
      </div>

      <div>
        <label class="form-label">Nombre de séquences <span style="color:red">*</span></label>
        <input type="number" name="nbr_squce" value="{{ old('nbr_squce',20) }}" class="form-input" min="1" required>
      </div>

      <div>
        <label class="form-label">Semestre <span style="color:red">*</span></label>
        <select name="id_sem" class="form-input" required>
          <option value="">Choisir…</option>
          @foreach($semestres as $s)
          <option value="{{ $s->id_sem }}" {{ old('id_sem')==$s->id_sem?'selected':'' }}>{{ $s->lib_sem }}</option>
          @endforeach
        </select>
        @error('id_sem')<div class="form-error">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="form-label">Spécialité <span style="color:red">*</span></label>
        <select name="id_spec" class="form-input" required>
          <option value="">Choisir…</option>
          @foreach($specialites as $sp)
          <option value="{{ $sp->id_spec }}" {{ old('id_spec')==$sp->id_spec?'selected':'' }}>{{ $sp->lib_spec }}</option>
          @endforeach
        </select>
        @error('id_spec')<div class="form-error">{{ $message }}</div>@enderror
      </div>

    </div>
    <div style="margin-top:20px;display:flex;justify-content:flex-end;">
      <button type="submit" class="btn btn-navy">Ajouter le cours</button>
    </div>
  </form>
</div>
@endsection
