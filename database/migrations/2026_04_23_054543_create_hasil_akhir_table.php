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
        Schema::create('hasil_akhir', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_kriteria');
            $table->bigInteger('skor')->default(0);
            $table->bigInteger('ranking')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_siswa')->references('id_siswa')->on('data_siswa')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hasil_akhir');
    }
};
