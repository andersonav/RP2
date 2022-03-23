<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLaudoModeloCapitulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laudo_modelo_capitulos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_capitulo');
            $table->unsignedInteger('laudo_modelo_id');

            $table->foreign('laudo_modelo_id')->references('id')->on('laudo_modelo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laudo_modelo_capitulos');
    }
}
