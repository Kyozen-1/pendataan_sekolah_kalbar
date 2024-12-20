<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_kecepatan_internets', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->longText('keterangan')->nullable();
            $table->enum('is_active', ['1', '0']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kecepatan_internets');
    }
};
