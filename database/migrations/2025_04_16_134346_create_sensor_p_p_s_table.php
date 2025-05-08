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
        Schema::create('sensor_p_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('parameter');         // ex: ''
            $table->string('ri');                // resource ID
            $table->timestamp('waktu');          // waktu pengambilan data
            $table->float('value');              // nilai sensor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensor_p_p_s');
    }
};
