<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllogAppsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allog_apps', function (Blueprint $table)
        {

            $table->string('appname', 16)->unique();
            $table->char('token', 32);
            $table->char('remote_addr', 15);
            $table->boolean('active')->default(true);

            $table->dateTime('updated');
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
        Schema::drop('allog_apps');
    }

}
