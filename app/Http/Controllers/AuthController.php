<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->mdp)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return match($user->role) {
                'administrateur' => redirect()->route('admin.dashboard'),
                'secretaire' => redirect()->route('secretaire.dashboard'),
                'enseignant' => redirect()->route('enseignant.dashboard'),
                default => redirect('/login'),
            };
        }

        throw ValidationException::withMessages([
            'email' => 'Identifiants incorrects.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}