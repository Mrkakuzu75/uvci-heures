<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    protected $table = 'semestres';
    protected $primaryKey = 'id_sem';
    protected $fillable = ['lib_sem'];

    public function cours() { return $this->hasMany(Cours::class, 'id_sem', 'id_sem'); }
}
