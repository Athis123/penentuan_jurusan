<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master\MasterSku;


class MasterSkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['kode' => 'QKS', 'deskripsi' => 'GEN01'],
            ['kode' => 'QKS', 'deskripsi' => 'HCDBEM130'],
            ['kode' => 'QKS', 'deskripsi' => 'GBG01'],
            ['kode' => 'QKS', 'deskripsi' => 'GBG02'],
            ['kode' => 'QKS', 'deskripsi' => 'GATGEN'],
            ['kode' => 'QKS', 'deskripsi' => 'BMK'],
            ['kode' => 'QKS', 'deskripsi' => 'BMZ'],
        ];

        foreach ($data as $item) {
            MasterSku::create($item);
        }
    }
}
