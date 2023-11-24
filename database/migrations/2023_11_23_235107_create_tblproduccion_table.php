<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tblProduccion', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->integer('idLinea')->nullable();
            $table->integer('idEstilo')->nullable();
            $table->integer('operariosNormal')->nullable();
            $table->integer('operariosRefuerzos')->nullable();
            $table->integer('uProducidas')->nullable();
            $table->integer('uIrregulares')->nullable();
            $table->integer('uRegulares')->nullable();
            $table->integer('metaNormal')->nullable();
            $table->decimal('totalHorasOrdinarias')->nullable();
            $table->decimal('totalHorasExtras')->nullable();
            $table->decimal('totalHorasTrabajadas')->nullable();
            $table->decimal('horasNoProducidas')->nullable();
            $table->decimal('horasProducidas')->nullable();
            $table->integer('metaAjustada')->nullable();
            $table->integer('eficiencia')->nullable();
            $table->integer('bonos')->nullable();
            $table->integer('maquinaMala')->nullable();
            $table->integer('noTrabajo')->nullable();
            $table->integer('entrenamiento')->nullable();
            $table->integer('cambioEstilo')->nullable();
            $table->string('observaciones', 250)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tblProduccion');
    }
};
