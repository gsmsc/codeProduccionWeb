<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tblProduccion', function (Blueprint $table) {
            $table->integer('idUsuario')->after('id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tblProduccion', function (Blueprint $table) {
            //
        });
    }
};
