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

    protected $casts = [
        'tanggal_tanam' => 'date',
    ];

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
