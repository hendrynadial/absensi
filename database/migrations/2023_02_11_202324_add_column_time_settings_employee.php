<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTimeSettingsEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('time_settings_employee', function (Blueprint $table) {
            $table->time('saturday_check_in_start')->nullable();
            $table->time('saturday_check_in_end')->nullable();
            $table->time('saturday_check_out_start')->nullable();
            $table->time('saturday_check_out_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
