<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_activites', function (Blueprint $table) {
            $table->id('id_typ_act');
            $table->string('lib_typ_act', 100);
            // ex : "Création de ressource", "Mise à jour de ressource"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_activites');
    }
};
