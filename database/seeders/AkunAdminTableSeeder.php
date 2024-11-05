<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AkunAdmin;

class AkunAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AkunAdmin::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make(12345678)
        ]);
    }
}
