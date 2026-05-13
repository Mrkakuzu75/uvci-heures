<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TypeActivite extends Model
{
    protected $table = 'types_activites';
    protected $primaryKey = 'id_typ_act';
    protected $fillable = ['lib_typ_act'];

    // Constantes pratiques
    const CREATION   = 1;
    const MISE_A_JOUR = 2;

    public function activites() { return $this->hasMany(Activite::class, 'id_typ_act', 'id_typ_act'); }
}
