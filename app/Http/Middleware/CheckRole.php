<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Pas connecté → page de connexion
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = Auth::user();

        // Rôle non autorisé → déconnexion + message
        if (!in_array($user->role, $roles)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' =>
                    'Accès refusé. Votre profil "' . ucfirst($user->role) . '" '
                    . 'ne vous autorise pas à accéder à cette section.'
                ]);
        }

        return $next($request);
    }
}
