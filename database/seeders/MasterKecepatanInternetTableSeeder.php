<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterKecepatanInternet;

class MasterKecepatanInternetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'nama' => 'Lambat',
                'keterangan' => 'Kecepatan internet 1 - 10 Mbps'
            ],
            [
                'nama' => 'Sedang',
                'keterangan' => 'Kecepatan Internet 10 - 50 Mbps'
            ],
            [
                'nama' => 'Cepat',
                'keterangan' => 'Kecepatan Internet 50 - 100 Mbps'
            ],
            [
                'nama' => 'Sangat Cepat',
                'keterangan' => 'Kecepatan Internet melebihi 100 Mbps'
            ],
        ];

        foreach ($datas as $data) {
            MasterKecepatanInternet::create([
                'nama' => $data['nama'],
                'keterangan' => $data['keterangan']
            ]);
        }
    }
}
