<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanaman extends Model
{
    use HasFactory;

    protected $table = 'tanaman';

    protected $fillable = [
        'nama_tanaman',
        'deskripsi',
        'tanggal_tanam',
        'panjang_daun',
        'lebar_daun',
        'foto',
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
        'panjang_daun' => 'float',
        'lebar_daun' => 'float',
    ];
}
