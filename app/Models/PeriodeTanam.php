<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeTanam extends Model
{
    use HasFactory;

    protected $table = 'periode_tanams';

    protected $fillable = [
        'tanaman_id',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status',
    ];

    // Relasi: PeriodeTanam milik satu Tanaman
    public function riwayats()
    {
        return $this->hasMany(RiwayatTanaman::class);
    }

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }
    
}
