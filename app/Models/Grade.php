<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';
    protected $primaryKey = 'id_grd';
    protected $fillable = ['lib_grd'];

    public function enseignants() { return $this->hasMany(Enseignant::class, 'id_grd', 'id_grd'); }
}
