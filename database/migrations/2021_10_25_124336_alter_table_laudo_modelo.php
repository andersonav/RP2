<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLaudoModelo extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::table('laudo_modelo', function (Blueprint $table) {
            $table->string('data_html', 255);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('laudo_modelo', function (Blueprint $table) {
            //
        });
    }
}
