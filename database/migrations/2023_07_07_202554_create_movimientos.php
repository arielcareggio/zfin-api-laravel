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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_movimiento_tipo');
            $table->unsignedBigInteger('id_banco_cuenta');
            $table->unsignedBigInteger('id_persona');
            $table->date('fecha');
            $table->float('monto');
            $table->text('url_archivo')->nullable(); //Por defecto null
            $table->text('comentario')->nullable(); //Por defecto null
            $table->timestamps(); //Crea created_at y updated_at
            
            $table->foreign('id_movimiento_tipo')->references('id')->on('movimientos_tipos')->onDelete('cascade');
            $table->foreign('id_banco_cuenta')->references('id')->on('bancos_cuentas')->onDelete('cascade');
            $table->foreign('id_persona')->references('id')->on('personas')->onDelete('cascade');

            $table->index('id_banco_cuenta'); // Crear índice en el campo id_banco_cuenta
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
