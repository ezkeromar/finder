<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('picture')->nullable();
            $table->integer('tjm')->nullable();
            $table->string('status')->nullable();    
            $table->integer('year_start_experience')->nullable();
            $table->string('profession')->nullable();
            $table->string('curriculum_vitae')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->integer('city_id')->nullable()->nullable();
            $table->integer('secteur_id')->nullable();
            $table->timestamp('disponibility_date')->nullable();
            $table->longText('cv_content')->nullable();
            $table->integer('client_id')->default('0')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
