<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentoModeloSubcapitulosn3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipamento_modelo_subcapitulosn3', function (Blueprint $table) {
            $table->id();
            $table->string('nome_sub_subcapitulo', 255);
            $table->unsignedInteger('equipamento_modelo_subcapitulo_id');
            $table->text('texto_padrao');
            $table->timestamps();

            $table->foreign('equipamento_modelo_subcapitulo_id')->references('id')->on('equipamento_modelo_subcapitulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipamento_modelo_subcapitulosn3');
    }
}
