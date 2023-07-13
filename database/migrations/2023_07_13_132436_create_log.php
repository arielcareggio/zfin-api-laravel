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
        Schema::create('log', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->unsignedBigInteger('id_user')->nullable(); //Debe permitir null
            $table->text('metodo')->nullable();
            $table->tinyInteger('is_error')->default(0); // tinyInteger para que sea de longitud = 1 
            $table->Integer('html_code')->default(0);
            $table->text('mensaje')->nullable();
            $table->text('json_entrada')->nullable();
            $table->text('json_salida')->nullable();
            $table->text('headers')->nullable();
            $table->string('origen')->limit(10)->nullable();
            $table->timestamps(); //Crea created_at y updated_at
            
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log');
    }
};
