<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTanaman extends Model
{
    use HasFactory;

    protected $table = 'riwayat_tanaman';

    protected $fillable = [
        'tanaman_id',
        'periode_tanam_id', // tambahkan ini
        'nama_periode',
        'waktu',
        'pupuk',
        'panjang_daun',
        'lebar_daun',
        'foto',
        'ph',
        'potasium',
        'phospor',
        'EC',
        'Nitrogen',
        'humidity',
        'temp',
    ];

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }

    public function periodeTanam()
    {
        return $this->belongsTo(PeriodeTanam::class);
    }
}
