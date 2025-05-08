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
        'tanaman_id',
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
            ->useLogName('periode_tanam')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Periode Tanam telah di-{$eventName}");
    }

    // Relasi: PeriodeTanam milik satu Tanaman
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    // Relasi: PeriodeTanam memiliki banyak RiwayatTanaman
    public function riwayats()
    {
        return $this->hasMany(RiwayatTanaman::class);
    }
}
