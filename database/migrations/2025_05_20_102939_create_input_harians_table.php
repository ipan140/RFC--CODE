<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('input_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanaman_id')->constrained('tanaman')->onDelete('cascade');
            $table->string('nama_periode');
            $table->timestamp('waktu');  
            $table->text('pupuk')->nullable();
            $table->float('panjang_daun')->nullable();        // Panjang daun (dalam cm)
            $table->float('lebar_daun')->nullable();          // Lebar daun (dalam cm)
            $table->string('foto')->nullable();               // Menyimpan nama file foto
            $table->float('ph')->nullable();
            $table->float('pota')->nullable();
            $table->float('phospor')->nullable();
            $table->float('EC')->nullable();
            $table->float('Nitrogen')->nullable();
            $table->float('humidity')->nullable();
            $table->float('temp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('input_harians');
    }
};
