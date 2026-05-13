<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ANNEE_ACADEMIQUE
     * Champs MCD : id_anee, dte_dbut, dte_fn, etat_anee
     *
     * Relation PORTE_SUR (0,N / 1,1) => une ACTIVITE porte sur une ANNEE_ACADEMIQUE
     */
    public function up(): void
    {
        Schema::create('annees_academiques', function (Blueprint $table) {
            $table->id('id_anee');
            $table->date('dte_dbut');                         // date début
            $table->date('dte_fn');                           // date fin
            $table->enum('etat_anee', ['en_cours', 'cloturee', 'a_venir'])
                  ->default('a_venir');
            $table->string('lib_anee', 20)->nullable();       // ex: "2025-2026"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annees_academiques');
    }
};
