<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activites', function (Blueprint $table) {
            $table->boolean('est_valide')->default(false);
            $table->timestamp('date_validation')->nullable();
            $table->foreignId('valide_par')
                  ->nullable()
                  ->constrained('utilisateurs', 'id_util')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('activites', function (Blueprint $table) {
            $table->dropForeign(['valide_par']);
            $table->dropColumn(['est_valide', 'date_validation', 'valide_par']);
        });
    }
};