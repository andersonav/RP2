<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentoModeloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipamento_modelo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome_modelo', 255);
            $table->string('tipo', 100);
            $table->unsignedBigInteger('user_id');
            $table->text('data_html');
            $table->string('descricao_modelo', 255);

            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipamento_modelo');
    }
}
