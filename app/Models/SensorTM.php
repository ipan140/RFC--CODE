<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorTM extends Model
{
    protected $table = 'sensor_t_m_s';  // Nama tabel di database
    protected $fillable = [
        'parameter', 'waktu', 'ri', 'value'
    ];  // Kolom-kolom yang dapat diisi

    public $timestamps = false; // Karena tabel kamu tidak pakai created_at & updated_at
}
