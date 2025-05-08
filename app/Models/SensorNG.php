<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SensorNG extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sensor_n_g_s';

    protected $fillable = [
        'parameter', 'waktu', 'ri', 'value'
    ];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sensor_ng') // Nama log spesifik
            ->logOnly(['parameter', 'waktu', 'ri', 'value'])
            ->setDescriptionForEvent(fn(string $eventName) => "SensorNG data has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
