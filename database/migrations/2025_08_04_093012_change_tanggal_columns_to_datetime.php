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
        Schema::table('order', function (Blueprint $table) {
            $table->dateTime('tanggal')->change();
            $table->dateTime('tanggal_tf')->nullable()->change();
        });

        Schema::table('repeat_order', function (Blueprint $table) {
            $table->dateTime('tanggal')->change();
            $table->dateTime('tanggal_tf')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->date('tanggal')->change();
            $table->date('tanggal_tf')->nullable()->change();
        });

        Schema::table('repeat_order', function (Blueprint $table) {
            $table->date('tanggal')->change();
            $table->date('tanggal_tf')->nullable()->change();
        });
    }
};
