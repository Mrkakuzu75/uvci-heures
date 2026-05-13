<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * COURS
     * Relations MCD :
     *   - APPARTENIR 1 (1,N / 1,1) => un COURS appartient à un SEMESTRE
     *   - CONCERNER 1 (1,N / 1,1)  => un COURS concerne une SPECIALITE
     *   - COMPOSER  (1,N / 1,1)    => un COURS est composé de SEQUENCES
     *
     * Champs MCD : id_crs, intit, filre (filière), niv (niveau),
     *              nbh_bse (nb heures base), nbr_crdt (nb crédits),
     *              nbr_squce (nb séquences), volHR (volume horaire total)
     */
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id('id_crs');
            $table->string('intit', 200);             // intitulé du cours
            $table->string('filre', 150);             // filière
            $table->enum('niv', ['L1','L2','L3','M1','M2']); // niveau
            $table->decimal('nbh_bse', 6, 2);         // nombre d'heures de base
            $table->unsignedTinyInteger('nbr_crdt');  // nombre de crédits
            $table->unsignedSmallInteger('nbr_squce'); // nombre de séquences prévu
            $table->decimal('volHR', 8, 2)->default(0); // volume horaire calculé

            // APPARTENIR 1 : lien avec le semestre
            $table->foreignId('id_sem')
                  ->constrained('semestres', 'id_sem');

            // CONCERNER 1 : lien avec la spécialité
            $table->foreignId('id_spec')
                  ->constrained('specialites', 'id_spec');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
