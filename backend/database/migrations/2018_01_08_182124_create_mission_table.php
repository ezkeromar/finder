<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('duration')->nullable();
            $table->text('description')->nullable();
            $table->float('tjm')->nullable();
            $table->string('status')->default('planned');
            $table->dateTime('date_start')->nullable();
            //$table->dateTime('created_at');
            //$table->dateTime('updated_at');
            $table->integer('city_id')->nullable();
            $table->integer('client_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('missions');
    }
}
