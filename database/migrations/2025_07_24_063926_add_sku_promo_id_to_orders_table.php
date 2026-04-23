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
            $table->unsignedBigInteger('sku_produk_id')->nullable()->after('nama_adv');
            $table->foreign('sku_produk_id')->references('id')->on('master_sku_produk')->onDelete('set null');
            $table->dropColumn('sku_produk');
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
            $table->dropForeign(['sku_produk_id']);
            $table->dropColumn('sku_produk_id');
            $table->string('sku_produk')->nullable();
        });
    }
};
