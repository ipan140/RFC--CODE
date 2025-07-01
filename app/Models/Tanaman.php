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

    // Cast tanggal_tanam ke datetime
    protected $casts = [
        'tanggal_tanam' => 'datetime',
    ];

    // Accessor: Format tanggal tanam
    public function getTanggalTanamFormattedAttribute()
    {
        return $this->tanggal_tanam ? $this->tanggal_tanam->format('d-m-Y H:i:s') : null;
    }

    // Relasi ke PeriodeTanam
    public function periodeTanams()
    {
        return $this->hasMany(PeriodeTanam::class, 'periode_tanams_id');
    }

    // Relasi ke Sampel (jika memang tanaman punya banyak sampel)

    // Logging activity
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
