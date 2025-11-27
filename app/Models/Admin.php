<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    // >>> Tambahkan INI
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function dataDiunggah(): HasMany
    {
        return $this->hasMany(DataSurvei::class, 'diunggah_oleh');
    }
}
