<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTanaman extends Model
{
    use HasFactory;

    protected $table = 'riwayat_tanams';

    protected $fillable = [
        'periode_tanam_id',
        'status',
        'tanggal',
        'keterangan',
    ];

    /**
     * Relasi: RiwayatTanaman milik satu PeriodeTanam
     */
    public function periodeTanam()
    {
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanam_id');
    }
}