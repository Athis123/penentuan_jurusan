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
            $table->unsignedBigInteger('kode_promo_id')->nullable()->after('kode_pos');
            $table->foreign('kode_promo_id')->references('id')->on('master_promo')->onDelete('set null');
            $table->dropColumn('kode_promo');
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
            $table->dropForeign(['kode_promo_id']);
            $table->dropColumn('kode_promo_id');
            $table->string('kode_promo')->nullable();
        });
    }
};
