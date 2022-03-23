<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentoModeloSubcapitulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipamento_modelo_subcapitulos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_subcapitulo');
            $table->string('texto_padrao', 255);
            $table->unsignedBigInteger('equipamento_modelo_capitulo_id');

            $table->foreign('equipamento_modelo_capitulo_id')->references('id')->on('equipamento_modelo_capitulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipamento_modelo_subcapitulos');
    }
}
