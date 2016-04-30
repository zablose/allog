<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllogRequestsAllogTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allog_requests_allog', function (Blueprint $table)
        {
            $table->smallIncrements('id');

            $table->string('http_user_agent')->nullable();
            $table->string('http_referer')->nullable();
            $table->char('remote_addr', 15);
            $table->char('request_method', 16);
            $table->string('request_uri');
            $table->dateTime('request_time')->nullable();

            $table->text('get')->nullable();
            $table->longText('post')->nullable();

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
        Schema::drop('allog_requests_allog');
    }

}
