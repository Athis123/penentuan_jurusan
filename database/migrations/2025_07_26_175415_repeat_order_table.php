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
        Schema::create('repeat_order', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->string('lok_gudang')->nullable();
            $table->string('ekpedisi')->nullable();
            $table->string('nama_crm')->nullable();
            $table->string('nama_adv')->nullable();
            $table->string('sku_produk')->nullable();
            $table->string('nama_produk')->nullable();
            $table->integer('qty_produk')->default(0);
            $table->bigInteger('harga_produk')->default(0);
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('kode_promo')->nullable();
            $table->bigInteger('ongkir')->default(0);
            $table->bigInteger('diskon_ongkir')->default(0);
            $table->bigInteger('admin_cod')->default(0);
            $table->bigInteger('diskon_admin_cod')->default(0);
            $table->string('pembayaran')->nullable();
            $table->bigInteger('total_pembayaran')->default(0);
            $table->string('bukti_tf')->nullable();
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
        Schema::dropIfExists('repeat_order');
    }
};
