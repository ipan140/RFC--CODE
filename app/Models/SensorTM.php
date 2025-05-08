<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SensorTM extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sensor_t_m_s';
    protected $fillable = [
        'parameter', 'waktu', 'ri', 'value'
    ];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sensor_tm') // Nama log (bebas)
            ->logOnly(['parameter', 'waktu', 'ri', 'value']) // Kolom yang dilog
            ->setDescriptionForEvent(fn(string $eventName) => "SensorTM data has been {$eventName}")
            ->logOnlyDirty() // Log hanya saat ada perubahan
            ->dontSubmitEmptyLogs(); // Abaikan jika tidak ada perubahan
    }
}
