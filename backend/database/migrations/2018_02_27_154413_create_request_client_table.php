<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_client', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->integer('client_id');
            $table->integer('consultant_id');
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
        Schema::dropIfExists('request_client');
    }
}
