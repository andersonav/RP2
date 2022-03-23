<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatTableClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('pessoa', 255);
            $table->string('cnpj', 15);
            $table->string('razao_social', 255);
            $table->string('nome_fantasia', 255);
            $table->string('inscricao_municipal');
            $table->string('inscricao_estadual', 255);
            $table->string('email');

            $table->string('phone')->nullable();
            $table->string('celular')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
