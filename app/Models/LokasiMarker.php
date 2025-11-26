<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LokasiMarker extends Model
{
    use HasFactory;

    protected $table = 'lokasi_marker';

    protected $fillable = [
        'id_data_survei',
        'pusat_lintang',
        'pusat_bujur',
    ];

    /**
     * Relasi: Satu LokasiMarker dimiliki oleh satu DataSurvei.
     * Anda dapat memanggil $lokasi->survei
     */
    public function survei(): BelongsTo
    {
        // Menghubungkan kolom 'id_data_survei' di tabel lokasi_marker dengan kolom 'id' di tabel data_survei
        return $this->belongsTo(DataSurvei::class, 'id_data_survei');
    }
}