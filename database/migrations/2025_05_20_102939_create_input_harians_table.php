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

            $table->foreignId('periode_tanam_id')->constrained('periode_tanams')->onDelete('cascade');

            // Tambahkan kolom kategori_sampel_id
            $table->foreignId('kategori_sampel_id')
                ->constrained('kategori_sampel')
                ->onDelete('cascade');

            $table->timestamp('waktu');
            $table->text('pupuk')->nullable();
            $table->float('panjang_daun')->nullable();
            $table->float('lebar_daun')->nullable();
            $table->string('foto')->nullable();
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
