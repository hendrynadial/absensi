<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pegawai');
            $table->string('nip');
            $table->string('nik');
            $table->string('nama');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->string('email')->nullable();
            $table->string('no_hp');
            $table->string('jabatan');
            $table->string('agama');
            $table->string('foto')->nullable();
            $table->string('status_pegawai');
            $table->date('tanggal_bergabung');
            $table->integer('id_time_settings_employee')->nullable();
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
        Schema::dropIfExists('employee');
    }
}
