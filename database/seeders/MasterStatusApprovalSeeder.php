<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStatusApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_status_approval')->insert([
            ['status' => 'Pending', 'keterangan' => 'Menunggu persetujuan', 'color' => 'warning'],
            ['status' => 'Disetujui', 'keterangan' => 'Telah disetujui', 'color' => 'success'],
            ['status' => 'Ditolak', 'keterangan' => 'Tidak disetujui', 'color' => 'danger'],
        ]);
    }
}
