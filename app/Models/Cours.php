<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $table      = 'cours';
    protected $primaryKey = 'id_crs';

    protected $fillable = [
        'intit', 'filre', 'niv', 'nbh_bse',
        'nbr_crdt', 'nbr_squce', 'volHR',
        'id_sem', 'id_spec',
    ];

    public function semestre()   { return $this->belongsTo(Semestre::class,   'id_sem',  'id_sem'); }
    public function specialite() { return $this->belongsTo(Specialite::class, 'id_spec', 'id_spec'); }
    public function sequences()  { return $this->hasMany(Sequence::class,     'id_crs',  'id_crs')->orderBy('ordre'); }
}
