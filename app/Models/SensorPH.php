<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorPh extends Model
{
    protected $table = 'sensor_p_h_s';  // Nama tabel di database
    protected $fillable = [
        'parameter', 'waktu', 'ri', 'value'
    ];  // Kolom-kolom yang dapat diisi

    public $timestamps = false; // Karena tabel kamu tidak pakai created_at & updated_at
}
