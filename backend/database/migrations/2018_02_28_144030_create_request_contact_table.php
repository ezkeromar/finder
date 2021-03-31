<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_contact', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mission_id');
            $table->integer('client_id');
            $table->integer('consultant_id');
            $table->date('date_start');
            $table->date('date_end');
            //$table->enum('status', ['confirmed', 'sent', 'rejected'])->default('sent');
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
        Schema::dropIfExists('request_contact');
    }
}
