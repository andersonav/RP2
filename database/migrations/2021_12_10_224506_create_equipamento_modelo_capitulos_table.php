<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentoModeloCapitulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipamento_modelo_capitulos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_capitulo');
            $table->unsignedBigInteger('equipamento_modelo_id');

            $table->foreign('equipamento_modelo_id')->references('id')->on('equipamento_modelo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipamento_modelo_capitulos');
    }
}
