<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_tanams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanaman_id')->constrained('tanaman')->onDelete('cascade');
            $table->string('nama_periode');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['Sudah', 'Belum'])->default('Belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Jika ada tabel lain yang tergantung pada periode_tanams, hapus terlebih dahulu
        Schema::dropIfExists('riwayat_tanamans');
        Schema::dropIfExists('sensor_data');

        // Hapus tabel periode_tanams
        Schema::dropIfExists('periode_tanams');
    }
};
