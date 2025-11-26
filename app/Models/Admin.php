<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel di database
    protected $table = 'admin';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
    ];

    // Kolom yang harus disembunyikan (saat dikirim sebagai JSON)
    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    /**
     * Relasi: Satu Admin dapat mengunggah banyak DataSurvei.
     */
    public function dataDiunggah(): HasMany
    {
        // Menghubungkan kolom 'id' di tabel admin dengan kolom 'diunggah_oleh' di tabel data_survei
        return $this->hasMany(DataSurvei::class, 'diunggah_oleh');
    }
}