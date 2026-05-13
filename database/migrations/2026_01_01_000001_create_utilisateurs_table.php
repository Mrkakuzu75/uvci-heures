<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id('id_util');
            $table->string('login', 100)->unique();
            $table->string('mdp');
            $table->string('email', 150)->unique();
            $table->enum('role', ['administrateur', 'secretaire', 'enseignant']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
