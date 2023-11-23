<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CAT_estilos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 150);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CAT_estilos');
    }
};
