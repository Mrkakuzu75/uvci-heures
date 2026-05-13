<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Statut extends Model
{
    protected $table = 'statuts';
    protected $primaryKey = 'id_stat';
    protected $fillable = ['lib_stat'];

    public function enseignants() { return $this->hasMany(Enseignant::class, 'id_stat', 'id_stat'); }
}
