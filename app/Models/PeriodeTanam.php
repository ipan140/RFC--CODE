<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PeriodeTanam extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'periode_tanams';

    protected $fillable = [
        'nama_tanaman',
        'deskripsi',
        'tanggal_tanam',
        'status',
        'tanaman_id' // Ditambahkan karena digunakan di relasi
    ];

    protected $casts = [
        'tanggal_tanam' => 'datetime',
        'status' => 'string' // Untuk enum lebih eksplisit
    ];

    protected $appends = [
        'tanggal_tanam_formatted'
    ];

    // Relasi ke Tanaman
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    // Relasi ke KategoriSampel
    public function kategoriSampels()
    {
        return $this->hasMany(KategoriSampel::class, 'periode_tanam_id');
    }

    // Relasi ke InputHarian (jika diperlukan)
    public function inputHarians()
    {
        return $this->hasManyThrough(
            InputHarian::class,
            KategoriSampel::class,
            'periode_tanam_id', // Foreign key pada tabel kategori_sampel
            'kategori_sampel_id', // Foreign key pada tabel input_harians
            'id', // Local key pada tabel periode_tanams
            'id' // Local key pada tabel kategori_sampel
        );
    }

    // Format tanggal tanam
    public function getTanggalTanamFormattedAttribute()
    {
        return $this->tanggal_tanam?->format('d-m-Y H:i:s');
    }

    // Konfigurasi Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('periode_tanam')
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Periode tanam telah {$eventName}")
            ->dontSubmitEmptyLogs();
    }
}