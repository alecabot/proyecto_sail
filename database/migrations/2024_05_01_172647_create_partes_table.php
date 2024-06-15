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
        Schema::create('partes', function (Blueprint $table) {
            $table->id();
            $table->string("profesor_dni")->nullable();
            $table->string("colectivo");
            $table->integer("puntos_penalizados");
            $table->longText("descripcion_detallada")->nullable();
            $table->foreignId('correccionaplicadas_id')->onDelete('cascade')->nullable();
            $table->foreignId('incidencia_id')->onDelete('cascade')->nullable();
            $table->foreignId("tramo_horario_id")->nullable();
            $table->foreign('profesor_dni')->references('dni')->on('profesors')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('tramo_horario_id')->references('id')->on('tramohorarios')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partes');
    }
};
