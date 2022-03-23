<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLaudoModeloSubcapitulosn3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laudo_modelo_subcapitulosn3', function (Blueprint $table) {
            $table->renameColumn('texto_apdrao', 'texto_padrao');

            $table->foreign('laudo_modelo_subcapitulo_id')->references('id')->on('laudo_modelo_subcapitulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laudo_modelo_subcapitulosn3', function (Blueprint $table) {
            //
        });
    }
}
