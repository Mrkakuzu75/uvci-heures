<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ACTIVITE
     * Relations MCD :
     *   - REALISER     (1,N / 1,1) => une ACTIVITE est réalisée par un ENSEIGNANT
     *   - CONCERNER    (1,1 / 1,N) => une ACTIVITE concerne une ANNEE_ACADEMIQUE
     *   - ETRE_DE_TYPE (1,1 / 1,N) => une ACTIVITE est d'un TYPE_ACTIVITE
     *   - PORTE_SUR    (0,N / 1,1) => une ACTIVITE porte sur une RESSOURCE
     *
     * Champs MCD : id_act, date_act, v_hor (volume horaire calculé)
     *
     * v_hor est calculé automatiquement selon la grille :
     *   TYPE "Création"     × niv_comp de la RESSOURCE → coefficient × nb séquences
     *   TYPE "Mise à jour"  × niv_comp de la RESSOURCE → coefficient × nb séquences
     */
    public function up(): void
    {
        Schema::create('activites', function (Blueprint $table) {
            $table->id('id_act');
            $table->date('date_act');                    // date de l'activité
            $table->decimal('v_hor', 8, 2)->default(0); // volume horaire calculé

            // REALISER : enseignant qui réalise l'activité
            $table->foreignId('id_ens')
                  ->constrained('enseignants', 'id_ens');

            // CONCERNER : année académique concernée
            $table->foreignId('id_anee')
                  ->constrained('annees_academiques', 'id_anee');

            // ETRE_DE_TYPE : type d'activité (création / mise à jour)
            $table->foreignId('id_typ_act')
                  ->constrained('types_activites', 'id_typ_act');

            // PORTE_SUR : ressource sur laquelle porte l'activité (0,N => nullable)
            $table->foreignId('id_ress')
                  ->nullable()
                  ->constrained('ressources', 'id_ress')
                  ->nullOnDelete();

            $table->text('observation')->nullable(); // remarques éventuelles
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activites');
    }
};
