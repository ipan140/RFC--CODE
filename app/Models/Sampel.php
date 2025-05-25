<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampel extends Model
{
    use HasFactory;

    protected $table = 'sampel'; // nama tabel sesuai database
    protected $fillable = ['nama', 'deskripsi', 'tanaman_id'];

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }
}

