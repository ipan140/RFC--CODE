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
            $table->string('nama_tanaman');
            $table->text('deskripsi')->nullable();
            $table->timestamp('tanggal_tanam')->nullable(); // ubah date ke timestamp nullable
            $table->enum('status', ['on going', 'selesai'])->default('on going');
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_tanams');
    }
};
