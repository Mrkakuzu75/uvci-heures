<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $table      = 'enseignants';
    protected $primaryKey = 'id_ens';

    protected $fillable = [
        'nom', 'pnom', 'tel', 'tx_horaire',
        'id_util', 'id_grd', 'id_stat', 'id_dep',
    ];

    // ── Relations ─────────────────────────────────────────────
    public function utilisateur() { return $this->belongsTo(Utilisateur::class, 'id_util', 'id_util'); }
    public function grade()       { return $this->belongsTo(Grade::class,       'id_grd',  'id_grd'); }
    public function statut()      { return $this->belongsTo(Statut::class,      'id_stat', 'id_stat'); }
    public function departement() { return $this->belongsTo(Departement::class, 'id_dep',  'id_dep'); }
    public function activites()   { return $this->hasMany(Activite::class,      'id_ens',  'id_ens'); }

    // ── Accesseurs utiles ─────────────────────────────────────
    public function getNomCompletAttribute(): string
    {
        return $this->pnom . ' ' . $this->nom;
    }

    public function getInitialesAttribute(): string
    {
        return strtoupper(substr($this->pnom, 0, 1) . substr($this->nom, 0, 1));
    }

    // ── Volume horaire total pour une année académique ────────
    public function volumeHoraireTotal(?int $idAnee = null): float
    {
        $query = $this->activites();
        if ($idAnee) {
            $query->where('id_anee', $idAnee);
        }
        return (float) $query->sum('v_hor');
    }

    // ── Heures complémentaires (au-delà du seuil statutaire) ──
    public function heuresComplementaires(float $seuilstatutaire = 192, ?int $idAnee = null): float
    {
        $total = $this->volumeHoraireTotal($idAnee);
        return max(0, $total - $seuilstatutaire);
    }
}
