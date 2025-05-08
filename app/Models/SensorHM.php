<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SensorHM extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sensor_h_m_s';

    protected $fillable = [
        'parameter', 'waktu', 'ri', 'value'
    ];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sensor_hm')
            ->logOnly(['parameter', 'waktu', 'ri', 'value'])
            ->setDescriptionForEvent(fn(string $eventName) => "SensorHM data has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
