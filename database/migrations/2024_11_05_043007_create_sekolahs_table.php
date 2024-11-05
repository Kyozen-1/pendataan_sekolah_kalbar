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
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_jenjang_sekolah_id')->nullable();
            $table->foreign('master_jenjang_sekolah_id')->references('id')->on('master_jenjang_sekolahs')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('nspn')->nullable();
            $table->string('email')->nullable();
            $table->enum('status_sekolah', ['negeri', 'swasta']);
            $table->enum('status_internet', ['iya', 'tidak']);
            $table->enum('jaringan_internet', ['wifi', 'data_seluler']);
            $table->foreignId('master_kecepatan_internet_id')->nullable();
            $table->foreign('master_kecepatan_internet_id')->references('id')->on('master_kecepatan_internets')->onDelete('cascade');
            $table->string('logo')->nullable();
            $table->string('nama_kepsek')->nullable();
            $table->string('tanda_tangan_kepsek')->nullable();
            $table->string('nip_kepsek')->nullable();
            $table->longText('alamat')->nullable();
            $table->enum('status_rawan_banjir', ['ya', 'tidak']);
            $table->string('total_siswa')->nullable();
            $table->string('no_hp')->nullable();
            $table->foreignId('master_kurikulum_id')->nullable();
            $table->foreign('master_kurikulum_id')->references('id')->on('master_kurikulums')->onDelete('cascade');
            $table->foreignId('provinsi_id')->nullable();
            $table->foreign('provinsi_id')->references('id')->on('wilayah')->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable();
            $table->foreign('kabupaten_id')->references('id')->on('wilayah')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable();
            $table->foreign('kecamatan_id')->references('id')->on('wilayah')->onDelete('cascade');
            $table->foreignId('kelurahan_id')->nullable();
            $table->foreign('kelurahan_id')->references('id')->on('wilayah')->onDelete('cascade');
            $table->enum('kode_penerbitan', ['DN', 'LN'])->nullable();
            $table->enum('kode_jenjang_pendidikan', ['D', 'M', 'PA', 'PB', 'PC'])->nullable();
            $table->enum('lama_program_belajar_smk', ['3', '4'])->nullable();
            $table->enum('akreditasi', ['A', 'B', 'C'])->nullable();
            $table->string('berkas_sertifikat_akreditasi')->nullable();
            $table->string('lng')->nullable();
            $table->string('lat')->nullable();
            $table->enum('is_dummy', ['0', '1']);
            $table->enum('is_active', ['1', '0']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
