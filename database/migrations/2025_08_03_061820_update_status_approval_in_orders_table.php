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
            $table->dropColumn('status_approval');
            $table->unsignedBigInteger('status_approval_id')->nullable()->after('bukti_tf');
            $table->foreign('status_approval_id')->references('id')->on('master_status_approval')->onDelete('set null');
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
            // Rollback: hapus FK dan kolom baru
            $table->dropForeign(['status_approval_id']);
            $table->dropColumn('status_approval_id');

            // Tambahkan kembali kolom lama jika perlu
            $table->string('status_approval')->nullable();
        });
    }
};
