<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movimientos_tipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cuenta');
            $table->unsignedBigInteger('id_tipo');
            $table->string('name')->limit(25);
            $table->string('icono')->limit(25);
            $table->timestamps(); //Crea created_at y updated_at
            
            $table->foreign('id_cuenta')->references('id')->on('cuentas')->onDelete('cascade');
            $table->foreign('id_tipo')->references('id')->on('tipos')->onDelete('cascade');

            $categorias = [
                //Ingresos
                ['id_cuenta' => -1, 'id_tipo' => 1, 'name' => 'Sueldo', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 1, 'name' => 'Bono', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 1, 'name' => 'Negocio', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 1, 'name' => 'Gratificación', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 1, 'name' => 'Horas Extras', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 1, 'name' => 'Aguinaldo', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 1, 'name' => 'Varios', 'icono' => null],
                
                //Egresos
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'General', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Supermercado', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Ropa', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Comida', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Niño (s)', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Alquiler', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Casa', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Seguros', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Salud', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Viajes', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Tiempo Libre', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Excursiones', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Regalos', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Electricidad', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Agua', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Gas', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Expensas', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Combustible', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Coche', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Educación', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Deportes', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Música', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Amigos', 'icono' => null],
                ['id_cuenta' => -1, 'id_tipo' => 2, 'name' => 'Varios', 'icono' => null],

            ];
    
            foreach ($categorias as $categoria) {
                DB::table('movimientos_tipos')->insert($categoria);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_tipos');
    }
};
