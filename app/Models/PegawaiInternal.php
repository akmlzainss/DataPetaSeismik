<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PegawaiInternal extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pegawai_internal';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'nip',
        'jabatan',
        'email_verified_at',
        'verification_token',
        'verification_token_expires_at',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_token_expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
    ];

    /**
     * Override password field name
     */
    public function getAuthPasswordName(): string
    {
        return 'kata_sandi';
    }

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    /**
     * Generate verification token
     */
    public function generateVerificationToken(): string
    {
        $token = Str::random(64);
        $this->verification_token = hash('sha256', $token);
        $this->verification_token_expires_at = Carbon::now()->addHour(); // Expire in 1 hour
        $this->save();
        
        return $token; // Return unhashed token untuk dikirim via email
    }

    /**
     * Check if email is verified
     */
    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        $this->email_verified_at = Carbon::now();
        $this->verification_token = null;
        $this->verification_token_expires_at = null;
        $this->is_approved = true; // Auto-approve jika email verified
        
        return $this->save();
    }

    /**
     * Check if can login (email verified OR manually approved)
     */
    public function canLogin(): bool
    {
        return $this->hasVerifiedEmail() || $this->is_approved;
    }

    /**
     * Check if verification token is valid
     */
    public function isValidVerificationToken(string $token): bool
    {
        if (is_null($this->verification_token) || is_null($this->verification_token_expires_at)) {
            return false;
        }

        if (Carbon::now()->isAfter($this->verification_token_expires_at)) {
            return false; // Token expired
        }

        return hash('sha256', $token) === $this->verification_token;
    }

    /**
     * Scope: Only approved users
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope: Pending approval
     */
    public function scopePendingApproval($query)
    {
        return $query->where('is_approved', false)
                     ->whereNull('email_verified_at');
    }

    /**
     * Relasi ke admin yang meng-approve (jika ada)
     */
    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
