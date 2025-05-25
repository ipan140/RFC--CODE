<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KategoriSampel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kategori_sampel'; // Nama tabel khusus

    protected $fillable = ['periode_tanam_id', 'nama', 'deskripsi'];

    // Relasi ke periode tanam (menentukan foreign key)
    public function periodeTanam()
    {
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanams_id');
    }

    // Relasi ke input harian
    public function inputHarians()
    {
        return $this->hasMany(InputHarian::class);
    }

    // Konfigurasi activity log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Input_harian')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Input Harian telah di-{$eventName}");
    }
}
