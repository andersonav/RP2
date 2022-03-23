<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignKeysTableLaudos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laudos', function (Blueprint $table) {
            $table->foreign('cliente_id')->references('id')->on('clientes');
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
        Schema::table('laudos', function (Blueprint $table) {
            //
        });
    }
}
