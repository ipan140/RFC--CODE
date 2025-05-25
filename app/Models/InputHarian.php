<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InputHarian extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'input_harians';

    protected $fillable = [
        'periode_tanam_id',
        'kategori_sampel_id',
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

    // Relasi: InputHarian milik satu PeriodeTanam
    public function periodeTanam()
    {
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanam_id');
    }

    // Relasi: InputHarian milik satu KategoriSampel
    public function kategoriSampel()
    {
        return $this->belongsTo(KategoriSampel::class, 'kategori_sampel_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanam_id');
    }

    // ✅ Tambahkan relasi ke Tanaman via PeriodeTanam
    public function tanaman()
    {
        return $this->hasOneThrough(
            \App\Models\Tanaman::class,
            \App\Models\PeriodeTanam::class,
            'id',                // foreign key di PeriodeTanam (ke Tanaman)
            'id',                // primary key di Tanaman
            'periode_tanam_id',  // foreign key di InputHarian (ke PeriodeTanam)
            'tanaman_id'         // foreign key di PeriodeTanam (ke Tanaman)
        );
    }
}
