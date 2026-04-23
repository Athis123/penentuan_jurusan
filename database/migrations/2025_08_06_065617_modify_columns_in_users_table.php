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
        Schema::table('users', function (Blueprint $table) {
            //hapus tabel tidak perlu
            $table->dropColumn('tim');
            $table->dropColumn('nik');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('remember_token');

            //tambah username untuk login
            $table->string('username')->unique()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //rollback
            $table->enum('tim', ['ADV', 'CS', 'CRM', 'FINANCE', 'INPUTER']);
            $table->string('nik');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            //hapus kolom username
            $table->dropColumn('username');
        });
    }
};
