<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master\MasterPromo;


class MasterPromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['kode' => null, 'deskripsi' => 'Potongan Harga'],
            ['kode' => 'GBG01', 'deskripsi' => 'Buku Buku Pemenuhan Apik untuk Tumbuh Kembang Anak Terbaik'],
            ['kode' => 'GATGEN', 'deskripsi' => 'Alat Tulis Generos'],
            ['kode' => 'GRM01', 'deskripsi' => 'Gift Puzzle Generos'],
            ['kode' => 'BMZ', 'deskripsi' => 'Buah Zuriat Bemomio'],
            ['kode' => 'BMK', 'deskripsi' => 'Kalender Kehamilan Bemomio'],
        ];

        foreach ($data as $item) {
            MasterPromo::create($item);
        }
    }
}
