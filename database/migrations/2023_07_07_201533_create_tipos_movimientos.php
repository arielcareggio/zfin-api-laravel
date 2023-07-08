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
        Schema::create('tipos_movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cuenta');
            $table->unsignedBigInteger('id_tipo');
            $table->string('name');
            $table->boolean('eliminado')->default(false);
            $table->timestamps(); //Crea created_at y updated_at
            
            $table->foreign('id_cuenta')->references('id')->on('cuentas')->onDelete('cascade');
            $table->foreign('id_tipo')->references('id')->on('tipos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_movimientos');
    }
};
