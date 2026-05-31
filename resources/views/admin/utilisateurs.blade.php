@extends('layouts.admin')
@section('title', 'Utilisateurs')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Utilisateurs</h1>
            <p class="text-gray-500 mt-1">Gestion des comptes utilisateurs</p>
        </div>
        <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter un utilisateur
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="text-gray-800">Liste des utilisateurs</h3>
            <span class="text-xs text-gray-400">{{ $utilisateurs->total() }} utilisateur(s)</span>
        </div>
        <div class="table-container">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date création</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($utilisateurs as $u)
                    <tr class="hover:bg-gray-50 transition">
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-medium text-sm">
                                    {{ strtoupper(substr($u->login, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $u->login }}</span>
                            </div>
                        </td>
                        <td class="text-gray-600">{{ $u->email }}</td>
                        <td>
                            @if($u->role === 'administrateur')
                                <span class="badge badge-purple">Administrateur</span>
                            @elseif($u->role === 'secretaire')
                                <span class="badge badge-info">Secrétaire</span>
                            @else
                                <span class="badge badge-success">Enseignant</span>
                            @endif
                        </td>
                        <td class="text-gray-500 text-sm">{{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : '—' }}</td>
                        <td class="text-right">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('admin.utilisateurs.edit', $u) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('admin.utilisateurs.destroy', $u) }}" 
                                      onsubmit="return confirm('Supprimer cet utilisateur ?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400">Aucun utilisateur</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($utilisateurs->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100">
            {{ $utilisateurs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection