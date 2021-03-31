<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDateRequestContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_contact', function (Blueprint $table) {
            //
            //$table->dateTime('date_start')->change();
            //$table->dateTime('date_end')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_contact', function (Blueprint $table) {
            //$table->date('date_start')->change();
            //$table->date('date_end')->change();
        });
    }
}
