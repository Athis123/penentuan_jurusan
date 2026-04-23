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
        Schema::table('repeat_order', function (Blueprint $table) {
            $table->date('tanggal_tf')->nullable()->after('pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repeat_order', function (Blueprint $table) {
            $table->dropColumn('tanggal_tf');
        });
    }
};
