<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->id(); // Kolom id auto-increment
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tanaman');
    }
};