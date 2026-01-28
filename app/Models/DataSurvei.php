<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'file_scan_asli',      // File asli untuk download pegawai
        'ukuran_file_asli',    // Ukuran dalam bytes
        'format_file_asli',    // Format: pdf, tiff, png, dll
    ];

    // Relasi lokasi() dihapus - sistem marker lama sudah tidak digunakan
    // Gunakan gridKotak() untuk sistem grid baru

    /**
     * Relasi many-to-many dengan GridKotak (SISTEM BARU)
     * Satu data survei bisa berada di banyak grid kotak
     */
    public function gridKotak(): BelongsToMany
    {
        return $this->belongsToMany(
            GridKotak::class,
            'grid_seismik',
            'data_survei_id',
            'grid_kotak_id'
        )
        ->withPivot(['assigned_by', 'assigned_at']);
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