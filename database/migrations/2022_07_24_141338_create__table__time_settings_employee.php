<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTimeSettingsEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_settings_employee', function (Blueprint $table) {
            $table->increments('id');
            $table->time('check_in_start');
            $table->time('check_in_end');
            $table->time('check_out_start');
            $table->time('check_out_end');
            $table->string('description');
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
        Schema::dropIfExists('time_settings_employee');
    }
}
