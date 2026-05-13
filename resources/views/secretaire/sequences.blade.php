@extends('layouts.app')
@section('title','Séquences')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','Séquences du cours')
@section('page-subtitle', $cours->intit . ' — ' . $cours->niv)

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('topbar-actions')
  <a href="{{ route('secretaire.cours') }}" class="btn btn-outline">
    ← Retour aux cours
  </a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr;gap:20px;">

  {{-- Info cours --}}
  <div style="background:var(--navy);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
    <div style="width:40px;height:40px;background:var(--green);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:var(--navy);flex-shrink:0;">{{ $cours->niv }}</div>
    <div style="flex:1;">
      <div style="font-weight:700;font-size:15px;color:#fff;">{{ $cours->intit }}</div>
      <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">{{ $cours->filre }} — {{ $cours->semestre?->lib_sem }} — {{ $cours->nbr_crdt }} crédits — {{ $cours->nbr_squce }} séquences prévues</div>
    </div>
    <div style="text-align:right;">
      <div style="font-weight:700;font-size:22px;color:var(--green);">{{ $sequences->count() }}/{{ $cours->nbr_squce }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">séquences créées</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr;gap:20px;">

    {{-- Liste des séquences --}}
    <div class="card">
      <div class="card-header">
        <h3>Séquences ({{ $sequences->count() }})</h3>
      </div>
      @forelse($sequences as $seq)
      <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-top:1px solid #F0F2F5;">
        <div style="width:32px;height:32px;border-radius:8px;background:var(--green-light);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:var(--green-dark);flex-shrink:0;">{{ $seq->ordre }}</div>
        <div style="flex:1;min-width:0;">
          <div style="font-weight:500;font-size:13.5px;">{{ $seq->ttre_seq }}</div>
          @if($seq->desc_seq)<div style="font-size:11px;color:var(--muted);margin-top:1px;">{{ $seq->desc_seq }}</div>@endif
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
          <a href="{{ route('secretaire.ressources', $seq) }}" class="btn btn-green btn-sm">
            {{ $seq->ressources_count }} ressource(s) →
          </a>
          <form method="POST" action="{{ route('secretaire.sequences.destroy', $seq) }}" onsubmit="return confirm('Supprimer cette séquence et ses ressources ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">×</button>
          </form>
        </div>
      </div>
      @empty
      <div style="padding:32px;text-align:center;color:var(--muted);font-size:13px;">Aucune séquence — ajoutez-en ci-dessous</div>
      @endforelse
    </div>

    {{-- Formulaire ajout séquence --}}
    <div class="card">
      <div class="card-header"><h3>Ajouter une séquence</h3></div>
      <form method="POST" action="{{ route('secretaire.sequences.store', $cours) }}" class="card-body" style="display:flex;flex-direction:column;gap:14px;">
        @csrf
        <div>
          <label class="form-label">Titre de la séquence <span style="color:red">*</span></label>
          <input type="text" name="ttre_seq" value="{{ old('ttre_seq') }}" class="form-input" placeholder="ex: Introduction aux variables" required>
          @error('ttre_seq')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="form-label">Description</label>
          <textarea name="desc_seq" class="form-input" rows="2" style="resize:vertical;" placeholder="Description optionnelle…">{{ old('desc_seq') }}</textarea>
        </div>
        <button type="submit" class="btn btn-navy">Ajouter la séquence</button>
      </form>
    </div>

  </div>
</div>
@endsection
