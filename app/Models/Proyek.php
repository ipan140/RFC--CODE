<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Proyek extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nama', 'deskripsi', 'tanggal', 'foto_proyek'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'deskripsi', 'tanggal', 'foto_proyek']) // Hanya log kolom ini
            ->useLogName('proyek') // Nama log
            ->logOnlyDirty() // Hanya log jika ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Proyek data has been {$eventName}");
    }
}
