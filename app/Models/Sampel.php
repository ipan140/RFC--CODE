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
    public function periodeTanam()
    {
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanam_id');
    }

    // Relasi: InputHarian milik satu KategoriSampel
    public function kategoriSampel()
    {
        return $this->belongsTo(KategoriSampel::class, 'kategori_sampel_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeTanam::class, 'periode_tanam_id');
    }

    // ✅ Tambahkan relasi ke Tanaman via PeriodeTanam
}

