<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    protected $table = 'annees_academiques';
    protected $primaryKey = 'id_anee';
    protected $fillable = ['dte_dbut', 'dte_fn', 'etat_anee', 'lib_anee'];
    protected $casts = ['dte_dbut' => 'date', 'dte_fn'   => 'date',];
    public function activites() { return $this->hasMany(Activite::class, 'id_anee', 'id_anee'); }

    public static function encours()
    {
        return static::where('etat_anee', 'en_cours')->first();
    }
}
