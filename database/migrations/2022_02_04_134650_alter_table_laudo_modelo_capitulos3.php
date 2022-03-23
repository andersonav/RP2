<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLaudoModeloCapitulos3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laudo_modelo_capitulos', function (Blueprint $table) {
            $table->text('texto_padrao')->nullable();
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
            $table->dropColumn('texto_padrao');
        });
    }
}
