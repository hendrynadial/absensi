<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePersonalCalender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_calender', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('curriculum_year_id')->nullable(); // Khusus Guru
            $table->integer('year')->nullable(); // Khusus Pegawai
            $table->date('date');
            $table->string('day');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('status')->nullable();
            $table->integer('id_calender_holiday')->nullable();
            $table->string('reason')->nullable();
            $table->boolean('status_check_in')->nullable();
            $table->string('photo_check_in')->nullable();
            $table->double('latitude_check_in')->nullable();
            $table->double('longitude_check_in')->nullable();
            $table->boolean('status_check_out')->nullable();
            $table->string('photo_check_out')->nullable();
            $table->double('latitude_check_out')->nullable();
            $table->double('longitude_check_out')->nullable();
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
        Schema::dropIfExists('personal_calender');
    }
}
