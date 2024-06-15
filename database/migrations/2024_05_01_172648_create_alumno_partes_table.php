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
        Schema::create('alumno_partes', function (Blueprint $table) {
            $table->id();
            $table->string("alumno_dni");
            $table->unsignedBigInteger('parte_id');
            $table->foreign('alumno_dni')->references('dni')->on('alumnos')->onUpdate('cascade');
            $table->foreign('parte_id')->references('id')->on('partes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno_partes');
    }
};
