<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLaudoModeloSubcapitulos extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('laudo_modelo_subcapitulos', function (Blueprint $table) {
            $table->id();
            
            $table->string('nome_subcapitulo');
            $table->unsignedBigInteger('laudo_modelo_capitulo_id');

            $table->foreign('laudo_modelo_capitulo_id')->references('id')->on('laudo_modelo_capitulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laudo_modelo_subcapitulos');
    }
}
