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
        Schema::create('cierres_mensuales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cuenta');
            $table->unsignedBigInteger('id_banco_cuenta');
            $table->unsignedBigInteger('id_persona');
            $table->float('total');
            $table->date('fecha');
            $table->boolean('eliminado')->default(false);
            $table->timestamps(); //Crea created_at y updated_at
            
            $table->foreign('id_cuenta')->references('id')->on('cuentas');
            $table->foreign('id_banco_cuenta')->references('id')->on('bancos_cuentas');
            $table->foreign('id_persona')->references('id')->on('personas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierres_mensuales');
    }
};
