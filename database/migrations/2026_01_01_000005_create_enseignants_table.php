<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ENSEIGNANT
     * Relations MCD :
     *   - AVOIR (0,N / 1,1)   => un UTILISATEUR peut être lié à un ENSEIGNANT
     *   - POSSEDER (1,1 / 1,N) => un ENSEIGNANT possède un GRADE
     *   - RELEVER (1,1 / 1,N)  => un ENSEIGNANT relève d'un STATUT
     *   - APPARTENIR (1,1 / 1,N) => un ENSEIGNANT appartient à un DEPARTEMENT
     */
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id('id_ens');
            $table->string('nom', 100);
            $table->string('pnom', 100);  // prénom
            $table->string('tel', 20)->nullable();
            $table->decimal('tx_horaire', 10, 2); // taux horaire (ex: 5000 FCFA/h)

            // AVOIR : lien avec le compte utilisateur (0,N côté UTILISATEUR)
            $table->foreignId('id_util')
                  ->nullable()
                  ->constrained('utilisateurs', 'id_util')
                  ->nullOnDelete();

            // POSSEDER : grade de l'enseignant
            $table->foreignId('id_grd')
                  ->constrained('grades', 'id_grd');

            // RELEVER : statut (Permanent / Vacataire)
            $table->foreignId('id_stat')
                  ->constrained('statuts', 'id_stat');

            // APPARTENIR : département de rattachement
            $table->foreignId('id_dep')
                  ->constrained('departements', 'id_dep');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
