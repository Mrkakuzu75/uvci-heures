<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RESSOURCE
     * Relations MCD :
     *   - CONTENIR (1,1 / 1,N) => une RESSOURCE est contenue dans une SEQUENCE
     *   - CLASSER  (1,1 / 1,N) => une RESSOURCE est classée dans un TYPE_RESSOURCE
     *   - PORTE_SUR (0,N / 1,1)=> une RESSOURCE peut être concernée par des ACTIVITES
     *
     * Champs MCD : id_ress, niv_comp (niveau complexité), dte_creat_ress, dte_maj_ress
     *
     * niv_comp sert au calcul du volume horaire (Niveau 1 / 2 / 3)
     * selon la grille du cahier des charges.
     */
    public function up(): void
    {
        Schema::create('ressources', function (Blueprint $table) {
            $table->id('id_ress');
            $table->unsignedTinyInteger('niv_comp');   // 1, 2 ou 3
            $table->date('dte_creat_ress');
            $table->date('dte_maj_ress')->nullable();

            // CONTENIR : séquence qui contient cette ressource
            $table->foreignId('id_seq')
                  ->constrained('sequences', 'id_seq')
                  ->cascadeOnDelete();

            // CLASSER : type de ressource
            $table->foreignId('id_typ_ress')
                  ->constrained('types_ressources', 'id_typ_ress');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressources');
    }
};
