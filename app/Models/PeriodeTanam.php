<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PeriodeTanam extends Model
{
    use HasFactory, LogsActivity;

    // Nama tabel yang benar di database
    protected $table = 'periode_tanams';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'nama_tanaman',
        'deskripsi',
        'tanggal_tanam',
        'status',
    ];

    // Casting atribut tanggal ke objek Carbon (datetime)
    protected $casts = [
        'tanggal_tanam' => 'datetime',
    ];

    // Aksesori custom untuk format tanggal tanam
    public function getTanggalTanamFormattedAttribute()
    {
        return $this->tanggal_tanam ? $this->tanggal_tanam->format('d-m-Y H:i:s') : null;
    }

    // Relasi ke Sampel (one-to-many)
    public function sampels()
    {
        return $this->hasMany(Sampel::class, 'periode_tanam_id');
    }

    // Relasi ke Tanaman (many-to-one)
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    // Konfigurasi Spatie activity log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('periode_tanam')
            ->logOnly([
                'nama_tanaman',
                'deskripsi',
                'tanggal_tanam',
                'status',
            ])
            ->logOnlyDirty();
    }
}
