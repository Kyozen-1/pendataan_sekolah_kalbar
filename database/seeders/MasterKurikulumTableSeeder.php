<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterKurikulum;

class MasterKurikulumTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'nama' => 'KTSP',
                'kode' => null
            ],
            [
                'nama' => 'KURIKULUM 2006',
                'kode' => null
            ],
            [
                'nama' => 'KURIKULUM 2013',
                'kode' => 'K13'
            ],
            [
                'nama' => 'KURIKULUM PENGGERAK',
                'kode' => null
            ],
            [
                'nama' => 'KURIKULUM PUSAT KEUNGGULAN',
                'kode' => null
            ],
            [
                'nama' => 'KURIKULUM MERDEKA',
                'kode' => 'KM'
            ],
        ];

        foreach ($datas as $data) {
            MasterKurikulum::create([
                'nama' => $data['nama'],
                'kode' => $data['kode']
            ]);
        }
    }
}
