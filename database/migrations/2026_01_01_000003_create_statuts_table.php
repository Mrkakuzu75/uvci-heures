<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuts', function (Blueprint $table) {
            $table->id('id_stat');
            $table->string('lib_stat', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuts');
    }
};
