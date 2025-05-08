<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Mitra extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama', 'lokasi', 'email', 'telepon', 'foto_mitra'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'lokasi', 'email', 'telepon', 'foto_mitra'])
            ->useLogName('mitra')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Mitra data has been {$eventName}");
    }
}
