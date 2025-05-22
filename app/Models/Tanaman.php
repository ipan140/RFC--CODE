<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tanaman extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tanaman';

    protected $fillable = [
        'nama_tanaman',
        'deskripsi',
        'tanggal_tanam',
        'status',
    ];

    // Ganti casting jadi timestamp (alias datetime)
    protected $casts = [
        'tanggal_tanam' => 'datetime',
    ];

    // Optional: accessor untuk format tampilannya
    public function getTanggalTanamFormattedAttribute()
    {
        return $this->tanggal_tanam ? $this->tanggal_tanam->format('d-m-Y H:i:s') : null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('tanaman')
            ->logOnly([
                'nama_tanaman',
                'deskripsi',
                'tanggal_tanam',
                'status',
            ])
            ->logOnlyDirty();
    }
}
