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
        Schema::create('riwayat_tanaman', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel tanaman
            $table->foreignId('tanaman_id')->constrained('tanaman')->onDelete('cascade');
            $table->foreignId('periode_tanam_id')->nullable()->constrained('periode_tanams')->onDelete('set null'); 

            $table->string('nama_periode')->nullable();
            $table->dateTime('waktu')->nullable();
            $table->string('pupuk')->nullable();
            $table->float('panjang_daun')->nullable();
            $table->float('lebar_daun')->nullable();
            $table->string('foto')->nullable();

            $table->float('ph')->nullable();
            $table->float('potasium')->nullable();
            $table->float('phospor')->nullable();
            $table->float('EC')->nullable();
            $table->float('Nitrogen')->nullable();
            $table->float('humidity')->nullable();
            $table->float('temp')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_tanaman');
    }
};
