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
            $table->string('kode')->nullable()->after('lok_gudang');
            $table->string('nomor_resi')->nullable()->after('status_approval');
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
            $table = $table->dropColumn(['kode', 'nomor_resi']);
        });
    }
};
