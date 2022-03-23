<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSubSubcapitulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laudo_modelo_subcapitulosn3', function (Blueprint $table) {
            $table->id();
            $table->string('nome_sub_subcapitulo', 255);
            $table->unsignedInteger('laudo_modelo_subcapitulo_id');
            $table->text('texto_apdrao');
            $table->timestamps();

            $table->foreign('laudo_modelo_subcapitluo_id')->references('id')->on('laudo_modelo_subcapitulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laudo_modelo_sub_subcapitulos');
    }
}
