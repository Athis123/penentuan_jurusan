<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->string('nisn')->nullable();
            $table->string('nama')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('no_hp')->nullable();
            $table->string('tmp_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_siswa');
    }
};
