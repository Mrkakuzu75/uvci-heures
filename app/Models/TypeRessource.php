<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TypeRessource extends Model
{
    protected $table = 'types_ressources';
    protected $primaryKey = 'id_typ_ress';
    protected $fillable = ['lib_typ_ress'];

    public function ressources() { return $this->hasMany(Ressource::class, 'id_typ_ress', 'id_typ_ress'); }
}
