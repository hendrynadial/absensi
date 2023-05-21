<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TimeSettingsTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_settings_teacher', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teacher_id');
            $table->integer('curriculum_year_id');
            $table->string('day');
            $table->time('check_in_start');
            $table->time('check_in_end');
            $table->time('check_out_start');
            $table->time('check_out_end');
            $table->string('description');
            $table->boolean('active')->nullable()->default(false);
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
        Schema::dropIfExists('time_settings_teacher');
    }
}
