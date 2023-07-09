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
        Schema::create('bancos_cuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_banco');
            $table->string('name')->nullable(); //Por defecto null
            $table->string('nro_cuenta')->nullable(); //Por defecto null
            $table->timestamps(); //Crea created_at y updated_at
            
            $table->foreign('id_banco')->references('id')->on('bancos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos_cuentas');
    }
};
