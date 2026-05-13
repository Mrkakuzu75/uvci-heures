@extends('layouts.app')
@section('title','Ressources')
@section('sidebar-role','Secrétaire Principal')
@section('page-title','Ressources pédagogiques')
@section('page-subtitle', $sequence->ttre_seq)

@section('sidebar-nav')
  <x-nav-item route="secretaire.dashboard"   label="Tableau de bord" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
  <x-nav-item route="secretaire.enseignants" label="Enseignants"     icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
  <x-nav-item route="secretaire.cours"       label="Cours"           icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
  <x-nav-item route="secretaire.activites"   label="Activités"       icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  <x-nav-item route="secretaire.paiements"   label="États de paiement" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
@endsection

@section('topbar-actions')
  <a href="{{ route('secretaire.sequences', $sequence->cours) }}" class="btn btn-outline">
    ← Retour aux séquences
  </a>
@endsection

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;">

  {{-- Fil d'Ariane --}}
  <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);flex-wrap:wrap;">
    <a href="{{ route('secretaire.cours') }}" style="color:var(--green-dark);text-decoration:none;">Cours</a>
    <span>›</span>
    <a href="{{ route('secretaire.sequences',$sequence->cours) }}" style="color:var(--green-dark);text-decoration:none;">{{ $sequence->cours?->intit }}</a>
    <span>›</span>
    <span style="color:var(--navy);font-weight:500;">{{ $sequence->ttre_seq }}</span>
  </div>

  {{-- Info niveaux --}}
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;">
    @foreach([1=>['label'=>'Niveau 1','desc'=>'Contenus simples + quiz + évaluations','color'=>'#4A90E2'],2=>['label'=>'Niveau 2','desc'=>'Niv.1 + activités interactives','color'=>'#00C07F'],3=>['label'=>'Niveau 3','desc'=>'Serious games, simulations','color'=>'#9B59B6']] as $niv=>$info)
    <div style="border:1px solid var(--border);border-radius:10px;padding:12px 14px;border-left:4px solid {{ $info['color'] }};">
      <div style="font-weight:600;font-size:13px;color:{{ $info['color'] }};">{{ $info['label'] }}</div>
      <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ $info['desc'] }}</div>
    </div>
    @endforeach
  </div>

  <div style="display:grid;grid-template-columns:1fr;gap:20px;">

    {{-- Liste des ressources --}}
    <div class="card">
      <div class="card-header">
        <h3>Ressources ({{ $ressources->count() }})</h3>
      </div>
      @php $nivColors=[1=>'#4A90E2',2=>'#00C07F',3=>'#9B59B6']; @endphp
      @forelse($ressources as $ress)
      <div style="display:flex;align-items:center;gap:14px;padding:12px 20px;border-top:1px solid #F0F2F5;">
        <div style="width:32px;height:32px;border-radius:8px;background:{{ $nivColors[$ress->niv_comp]??'#ccc' }}22;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:{{ $nivColors[$ress->niv_comp]??'#ccc' }};flex-shrink:0;border:1px solid {{ $nivColors[$ress->niv_comp]??'#ccc' }}44;">N{{ $ress->niv_comp }}</div>
        <div style="flex:1;min-width:0;">
          <div style="font-weight:500;font-size:13px;">{{ $ress->typeRessource?->lib_typ_ress }}</div>
          <div style="font-size:11px;color:var(--muted);">Créée le {{ $ress->dte_creat_ress?->format('d/m/Y') }}{{ $ress->dte_maj_ress ? ' · Mise à jour : '.$ress->dte_maj_ress->format('d/m/Y') : '' }}</div>
        </div>
        <form method="POST" action="{{ route('secretaire.ressources.destroy', $ress) }}" onsubmit="return confirm('Supprimer cette ressource ?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
        </form>
      </div>
      @empty
      <div style="padding:32px;text-align:center;color:var(--muted);font-size:13px;">Aucune ressource — ajoutez-en ci-dessous</div>
      @endforelse
    </div>

    {{-- Formulaire ajout ressource --}}
    <div class="card">
      <div class="card-header"><h3>Ajouter une ressource</h3></div>
      <form method="POST" action="{{ route('secretaire.ressources.store', $sequence) }}" class="card-body" style="display:flex;flex-direction:column;gap:14px;">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
          <div>
            <label class="form-label">Niveau de complexité <span style="color:red">*</span></label>
            <select name="niv_comp" class="form-input" required>
              <option value="">Choisir…</option>
              <option value="1" {{ old('niv_comp')==1?'selected':'' }}>Niveau 1 — Simple</option>
              <option value="2" {{ old('niv_comp')==2?'selected':'' }}>Niveau 2 — Interactif</option>
              <option value="3" {{ old('niv_comp')==3?'selected':'' }}>Niveau 3 — Serious game</option>
            </select>
            @error('niv_comp')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="form-label">Type de ressource <span style="color:red">*</span></label>
            <select name="id_typ_ress" class="form-input" required>
              <option value="">Choisir…</option>
              @foreach($typesRessources as $tr)
              <option value="{{ $tr->id_typ_ress }}" {{ old('id_typ_ress')==$tr->id_typ_ress?'selected':'' }}>{{ $tr->lib_typ_ress }}</option>
              @endforeach
            </select>
            @error('id_typ_ress')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="form-label">Date de création <span style="color:red">*</span></label>
            <input type="date" name="dte_creat_ress" value="{{ old('dte_creat_ress', date('Y-m-d')) }}" class="form-input" required>
          </div>
        </div>
        <button type="submit" class="btn btn-navy">Ajouter la ressource</button>
      </form>
    </div>

  </div>
</div>
@endsection
