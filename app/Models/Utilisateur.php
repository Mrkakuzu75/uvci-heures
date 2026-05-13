<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table      = 'utilisateurs';
    protected $primaryKey = 'id_util';

    protected $fillable = ['login', 'mdp', 'email', 'role'];
    protected $hidden   = ['mdp'];

    // ── Désactiver remember_token (colonne absente de la table) ──
    public function getRememberTokenName(): ?string
    {
        return null; // empêche Laravel de chercher remember_token
    }

    // ── Laravel Auth attend "password" — on redirige vers "mdp" ──
    public function getAuthPassword(): string
    {
        return $this->mdp;
    }

    // ── Relations ─────────────────────────────────────────────
    public function enseignant()
    {
        return $this->hasOne(Enseignant::class, 'id_util', 'id_util');
    }

    // ── Helpers rôles ─────────────────────────────────────────
    public function isAdmin(): bool      { return $this->role === 'administrateur'; }
    public function isSecretaire(): bool { return $this->role === 'secretaire'; }
    public function isEnseignant(): bool { return $this->role === 'enseignant'; }

    public function redirectRoute(): string
    {
        return match($this->role) {
            'administrateur' => 'admin.dashboard',
            'secretaire'     => 'secretaire.dashboard',
            'enseignant'     => 'enseignant.dashboard',
            default          => 'login',
        };
    }
}
