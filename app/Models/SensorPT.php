<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorPT extends Model
{
    protected $table = 'sensor_p_t_s';  // Nama tabel di database
    protected $fillable = [
        'parameter', 'waktu', 'ri', 'value'
    ];  // Kolom-kolom yang dapat diisi

    public $timestamps = false; 
}
