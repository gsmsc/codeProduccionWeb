<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('CAT_estilos', function (Blueprint $table) {
            $table->string('codigo', 50)->after('id')->nullable();
            $table->string('idCliente', 150)->after('codigo')->nullable();
            $table->string('idDivision', 150)->after('idCliente')->nullable();
            $table->string('idSubcategoria', 150)->after('idDivision')->nullable();
            $table->string('referencia1', 150)->after('idSubcategoria')->nullable();
            $table->string('referencia2', 150)->after('referencia1')->nullable();
        });
    }

    public function down()
    {
        Schema::table('CAT_estilos', function (Blueprint $table) {
            //
        });
    }
};
