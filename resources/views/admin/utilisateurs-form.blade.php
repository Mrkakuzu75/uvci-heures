@extends('layouts.admin')
@section('title', $utilisateur ? 'Modifier utilisateur' : 'Nouvel utilisateur')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('admin.utilisateurs') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">{{ $utilisateur ? 'Modifier' : 'Créer' }} un utilisateur</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ $utilisateur ? route('admin.utilisateurs.update', $utilisateur) : route('admin.utilisateurs.store') }}">
                @csrf
                @if($utilisateur) @method('PUT') @endif

                <div class="space-y-5">
                    <!-- Login -->
                    <div>
                        <label class="form-label">Login <span class="text-red-500">*</span></label>
                        <input type="text" name="login" class="form-input @error('login') border-red-500 @enderror" 
                               value="{{ old('login', $utilisateur?->login) }}" required placeholder="ex: jdupont">
                        @error('login')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" class="form-input @error('email') border-red-500 @enderror" 
                               value="{{ old('email', $utilisateur?->email) }}" required placeholder="ex: jdupont@uvci.edu.ci">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="form-label">{{ $utilisateur ? 'Nouveau mot de passe (laisser vide pour ne pas changer)' : 'Mot de passe' }} @if(!$utilisateur)<span class="text-red-500">*</span>@endif</label>
                        <input type="password" name="password" class="form-input @error('password') border-red-500 @enderror" 
                               {{ !$utilisateur ? 'required' : '' }} placeholder="8 caractères minimum">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmation mot de passe -->
                    @if($utilisateur)
                    <div>
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Retapez le mot de passe">
                    </div>
                    @else
                    <div>
                        <label class="form-label">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" class="form-input" required placeholder="Retapez le mot de passe">
                    </div>
                    @endif

                    <!-- Rôle -->
                    <div>
                        <label class="form-label">Rôle <span class="text-red-500">*</span></label>
                        <select name="role" class="form-select @error('role') border-red-500 @enderror" required>
                            <option value="administrateur" {{ old('role', $utilisateur?->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            <option value="secretaire" {{ old('role', $utilisateur?->role) == 'secretaire' ? 'selected' : '' }}>Secrétaire</option>
                            <option value="enseignant" {{ old('role', $utilisateur?->role) == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $utilisateur ? 'Mettre à jour' : 'Créer' }}
                    </button>
                    <a href="{{ route('admin.utilisateurs') }}" class="btn btn-secondary flex-1">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection