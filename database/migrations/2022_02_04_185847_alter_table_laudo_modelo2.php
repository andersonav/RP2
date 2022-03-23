<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLaudoModelo2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laudo_modelo', function (Blueprint $table) {
            $table->text('data_html_header')->nullable();
            $table->text('data_html_footer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laudo_modelo', function (Blueprint $table) {
            $table->dropColumn('data_html_header');
            $table->dropColumn('data_html_footer');
        });
    }
}
