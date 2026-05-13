<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_ressources', function (Blueprint $table) {
            $table->id('id_typ_ress');
            $table->string('lib_typ_ress', 100);
            // ex : "Contenu textuel", "Vidéo pédagogique", "Document", "Quiz",
            //       "Activité interactive", "Évaluation"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_ressources');
    }
};
