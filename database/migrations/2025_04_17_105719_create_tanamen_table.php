<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->text('deskripsi')->nullable();
            // ubah date ke timestamp nullable
            $table->timestamp('tanggal_tanam')->nullable();
            $table->enum('status', ['on going', 'selesai'])->default('on going');
            // pakai timestamps() bawaan Laravel (created_at & updated_at)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanaman');
    }
};
