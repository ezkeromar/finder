<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserDiplomaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_diploma', function (Blueprint $table) {
            $table->integer('year');
            $table->string('school')->nullable();
            $table->string('speciality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_diploma', function (Blueprint $table) {
            $table->dropColumn(['year', 'school', 'speciality']);
        });
    }
}
