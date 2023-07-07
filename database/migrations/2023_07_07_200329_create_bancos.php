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
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cuenta');
            $table->unsignedBigInteger('id_countrie');
            $table->string('name');
            $table->boolean('eliminado')->default(false);
            $table->timestamps(); //Crea created_at y updated_at
            
            $table->foreign('id_cuenta')->references('id')->on('cuentas');
            $table->foreign('id_countrie')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
