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
    public function up()
    {
        Schema::create('riwayat_tanamans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_tanam_id');
            $table->string('status'); // contoh: "Tanam", "Panen", "Pemupukan", dll
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        
            $table->foreign('periode_tanam_id')->references('id')->on('periode_tanams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_tanaman');
    }
};
