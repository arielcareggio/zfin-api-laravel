<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->after('name')->nullable(); //Agrega la columna 'last_name' después de la columna 'name'.
            $table->unsignedBigInteger('id_countrie')->after('last_name')->nullable(); // El tipo 'unsignedBigInteger', es un entero sin signo de 64 bits.
            $table->string('phone')->after('id_countrie')->nullable();

            $table->foreign('id_countrie')->references('id')->on('countries');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_countrie']);
            $table->dropColumn(['last_name', 'id_countrie', 'phone']);
        });
    }
};
