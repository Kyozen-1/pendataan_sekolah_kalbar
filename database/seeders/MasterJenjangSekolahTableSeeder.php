<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterJenjangSekolah;

class MasterJenjangSekolahTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas  = ['SD', 'SMP', 'SMA', 'SMK', 'SLB'];
        foreach ($datas as $data) {
            MasterJenjangSekolah::create([
                'nama' => $data
            ]);
        }
    }
}
