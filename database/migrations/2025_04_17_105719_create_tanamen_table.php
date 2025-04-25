<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_tanam')->nullable();        // Tanggal penanaman
            $table->float('panjang_daun')->nullable();        // Panjang daun (dalam cm)
            $table->float('lebar_daun')->nullable();          // Lebar daun (dalam cm)
            $table->string('foto')->nullable();               // Path atau nama file foto
            $table->timestamps();                             // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tanaman');
    }
};
