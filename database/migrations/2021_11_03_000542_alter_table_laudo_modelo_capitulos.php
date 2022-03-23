<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLaudoModeloCapitulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laudo_modelo_capitulos', function (Blueprint $table) {
            $table->unsignedBigInteger('laudo_modelo_id');
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
        Schema::table('laudo_modelo_capitulos', function (Blueprint $table) {
            //
        });
    }
}
