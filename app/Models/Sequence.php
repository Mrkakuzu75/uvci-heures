<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    protected $table      = 'sequences';
    protected $primaryKey = 'id_seq';

    protected $fillable = ['ttre_seq', 'desc_seq', 'id_crs', 'ordre'];

    public function cours()     { return $this->belongsTo(Cours::class, 'id_crs', 'id_crs'); }
    public function ressources(){ return $this->hasMany(Ressource::class, 'id_seq', 'id_seq'); }
}
