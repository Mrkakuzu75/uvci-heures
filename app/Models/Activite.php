<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AdminController;

class Activite extends Model
{
    protected $table      = 'activites';
    protected $primaryKey = 'id_act';

    protected $fillable = [
        'date_act','v_hor','observation',
        'id_ens','id_anee','id_typ_act','id_ress',
    ];

    protected $casts = ['date_act' => 'date'];

    // ── Relations ─────────────────────────────────────────────
    public function enseignant()   { return $this->belongsTo(Enseignant::class,    'id_ens',     'id_ens'); }
    public function annee()        { return $this->belongsTo(AnneeAcademique::class,'id_anee',   'id_anee'); }
    public function typeActivite() { return $this->belongsTo(TypeActivite::class,  'id_typ_act', 'id_typ_act'); }
    public function ressource()    { return $this->belongsTo(Ressource::class,     'id_ress',    'id_ress'); }

    // ── Calcul volume horaire ─────────────────────────────────
    public static function calculerVolumeHoraire(int $typeActiviteId, int $niveauComplexite, int $nbSequences): float
    {
        $config = AdminController::loadConfig();
        $coeff  = $config['coefficients'][$typeActiviteId][$niveauComplexite] ?? 0;
        return round($coeff * $nbSequences, 2);
    }

    // ── Boot : calcul automatique avant sauvegarde ────────────
    protected static function booted(): void
    {
        static::saving(function (Activite $activite) {
            if ($activite->id_ress && $activite->id_typ_act) {
                $ressource   = Ressource::with('sequence.cours')->find($activite->id_ress);
                $nbSequences = $ressource?->nb_sequences ?? 0;
                $niv         = $ressource?->niv_comp ?? 1;
                $activite->v_hor = self::calculerVolumeHoraire($activite->id_typ_act, $niv, $nbSequences);
            }
        });
    }
}
