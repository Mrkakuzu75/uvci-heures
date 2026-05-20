<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogConnexion extends Model
{
    protected $table      = 'logs_connexion';
    public    $timestamps = false;

    protected $fillable = [
        'id_util', 'ip', 'user_agent', 'action', 'statut',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_util', 'id_util');
    }
}
