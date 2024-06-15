<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parte_incidencias', function (Blueprint $table) {
            $table->unsignedBigInteger('parte_id')->unique();
            $table->unsignedBigInteger('incidencia_id');
            $table->foreign('parte_id')->references('id')->on('partes');
            $table->foreign('incidencia_id')->references('id')->on('incidencias')->onDelete('cascade');;
            $table->primary(['incidencia_id', 'parte_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parte_incidencias');
    }
};
