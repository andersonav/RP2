<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLaudos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laudos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('tipo_laudo_id');
            $table->string('nome_laudo', 255);
            $table->string('anexo_url', 255);
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
        Schema::dropIfExists('laudos');
    }
}
