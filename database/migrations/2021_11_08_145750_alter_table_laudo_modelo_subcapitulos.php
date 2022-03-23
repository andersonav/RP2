<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLaudoModeloSubcapitulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laudo_modelo_subcapitulos', function (Blueprint $table) {
            $table->string('texto_padrao', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laudo_modelo_subcapitulos', function (Blueprint $table) {
            //
        });
    }
}
