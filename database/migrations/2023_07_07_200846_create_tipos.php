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
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('tipos')->limit(7);
        });

        DB::table('tipos')->insert([
            'id' => 1,
            'tipos' => 'Ingreso',
        ]);

        DB::table('tipos')->insert([
            'id' => 2,
            'tipos' => 'Egreso',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos');
    }
};
