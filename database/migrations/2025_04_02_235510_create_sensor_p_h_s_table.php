<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_p_h_s', function (Blueprint $table) {
            $table->id();
            $table->string('parameter');         // ex: 'ph'
            $table->string('ri');                // resource ID
            $table->timestamp('waktu');          // waktu pengambilan data
            $table->float('value');              // nilai sensor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensor_p_h_s');
    }
};