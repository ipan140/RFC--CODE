<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InputHarian extends Model
{
    use HasFactory, LogsActivity;

    // Ganti nama tabel ke 'input_harians'
    protected $table = 'input_harians';

    protected $fillable = [
        'tanaman_id',
        'periode_tanam_id', // Menambahkan periode_tanam_id di sini
        'nama_periode',
        'waktu',
        'pupuk',
        'panjang_daun',
        'lebar_daun',
        'ph',
        'pota',
        'phospor',
        'EC',
        'Nitrogen',
        'humidity',
        'temp',
        'foto',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Input_harian')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Input Harian telah di-{$eventName}");
    }

    // Relasi: InputHarian milik satu Tanaman
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    // Relasi: InputHarian memiliki banyak RiwayatTanaman (jika relevan)
    public function riwayats()
    {
        return $this->hasMany(RiwayatTanaman::class);
    }

    // Relasi: InputHarian milik satu PeriodeTanam
    public function periode()
    {
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanam_id'); // Pastikan periode_tanam_id sudah ada di migrasi
    }
}
