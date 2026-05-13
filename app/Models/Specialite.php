<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    protected $table = 'specialites';
    protected $primaryKey = 'id_spec';
    protected $fillable = ['lib_spec'];

    public function cours() { return $this->hasMany(Cours::class, 'id_spec', 'id_spec'); }
}
