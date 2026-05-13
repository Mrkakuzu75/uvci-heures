<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $table = 'departements';
    protected $primaryKey = 'id_dep';
    protected $fillable = ['lib_dep'];

    public function enseignants() { return $this->hasMany(Enseignant::class, 'id_dep', 'id_dep'); }
}
