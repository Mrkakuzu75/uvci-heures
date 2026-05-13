<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $table      = 'ressources';
    protected $primaryKey = 'id_ress';

    protected $fillable = [
        'niv_comp', 'dte_creat_ress', 'dte_maj_ress',
        'id_seq', 'id_typ_ress',
    ];

    protected $casts = [
        'dte_creat_ress' => 'date',
        'dte_maj_ress'   => 'date',
    ];

    public function sequence()     { return $this->belongsTo(Sequence::class,     'id_seq',      'id_seq'); }
    public function typeRessource(){ return $this->belongsTo(TypeRessource::class, 'id_typ_ress', 'id_typ_ress'); }
    public function activites()    { return $this->hasMany(Activite::class,        'id_ress',     'id_ress'); }

    // Nombre de séquences du cours (pour calcul v_hor)
    public function getNbSequencesAttribute(): int
    {
        return $this->sequence?->cours?->nbr_squce ?? 0;
    }
}
