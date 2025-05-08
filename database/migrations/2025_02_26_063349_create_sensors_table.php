<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorsTable extends Migration
{
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('parameter');         // ex: suhu, kelembaban, dll
            $table->string('ri');                // resource ID
            $table->timestamp('waktu');          // waktu pengambilan data
            $table->float('value');              // nilai sensor
            $table->timestamps();                // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensors');
    }
}
