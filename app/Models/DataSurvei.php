<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DataSurvei extends Model
{
    use HasFactory;
    protected $table = 'data_survei';
    // Menentukan bahwa kolom tahun adalah tipe data integer (year)
    protected $casts = [
        'tahun' => 'integer',
    ];
    // Kolom yang bisa diisi massal
    protected $fillable = [
        'judul',
        'ketua_tim', // Tambahkan ini
        'tahun',
        'tipe',
        'wilayah',
        'deskripsi',
        'gambar_pratinjau',
        'tautan_file',
        'diunggah_oleh',
    ];

    /**
     * Relasi: Satu DataSurvei memiliki satu LokasiMarker.
     * Anda dapat memanggil $survei->lokasi
     */
    public function lokasi(): HasOne
    {
        // Menghubungkan kolom 'id' di tabel data_survei dengan kolom 'id_data_survei' di tabel lokasi_marker
        return $this->hasOne(LokasiMarker::class, 'id_data_survei');
    }

    /**
     * Relasi: Satu DataSurvei dimiliki oleh satu Admin.
     * Anda dapat memanggil $survei->pengunggah
     */
    public function pengunggah(): BelongsTo
    {
        // Menghubungkan kolom 'diunggah_oleh' di tabel data_survei dengan kolom 'id' di tabel admin
        return $this->belongsTo(Admin::class, 'diunggah_oleh');
    }

    
}