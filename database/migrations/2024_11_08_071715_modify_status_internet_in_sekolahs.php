<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `sekolahs` MODIFY `status_sekolah` ENUM('negeri', 'swasta') NULL");
        DB::statement("ALTER TABLE `sekolahs` MODIFY `status_internet` ENUM('iya', 'tidak') NULL");
        DB::statement("ALTER TABLE `sekolahs` MODIFY `jaringan_internet` ENUM('wifi', 'data_seluler') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            //
        });
    }
};
