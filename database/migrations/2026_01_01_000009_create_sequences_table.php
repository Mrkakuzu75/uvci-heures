<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SEQUENCE
     * Relations MCD :
     *   - COMPOSER (1,1 / 1,N) => une SEQUENCE est composée dans un COURS
     *   - CONTENIR (1,N / 1,1) => une SEQUENCE contient des RESSOURCES
     *
     * Champs MCD : id_seq, ttre_seq (titre), desc_seq (description)
     */
    public function up(): void
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->id('id_seq');
            $table->string('ttre_seq', 200);          // titre de la séquence
            $table->text('desc_seq')->nullable();      // description

            // COMPOSER : séquence rattachée à un cours
            $table->foreignId('id_crs')
                  ->constrained('cours', 'id_crs')
                  ->cascadeOnDelete();

            $table->unsignedSmallInteger('ordre')->default(1); // ordre dans le cours
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequences');
    }
};
