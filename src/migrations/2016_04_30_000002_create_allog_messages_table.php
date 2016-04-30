<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllogMessagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allog_messages', function (Blueprint $table)
        {
            $table->tinyInteger('id', true, true);

            $table->string('type', 16)->default('info');
            $table->string('message')->default();

            $table->dateTime('created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('allog_messages');
    }

}
