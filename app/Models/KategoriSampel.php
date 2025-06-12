<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KategoriSampel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kategori_sampel';

    protected $fillable = ['periode_tanam_id', 'nama', 'deskripsi'];

    public function periodeTanam()
    {
        // Perbaikan: Sesuaikan dengan nama kolom di database (periode_tanam_id)
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanam_id');
    }

    public function inputHarians()
    {
        return $this->hasMany(InputHarian::class, 'kategori_sampel_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('kategori_sampel') // Diubah sesuai dengan model ini
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Kategori Sampel telah di-{$eventName}");
    }
}