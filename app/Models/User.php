<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PerusahaanProfile;

/**
 * @property-read \App\Models\MahasiswaProfile|null $mahasiswaProfile
 * @property-read \App\Models\PerusahaanProfile|null $perusahaanProfile
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'role',
        'no_hp', // Added phone field
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function perusahaanProfile()
    {
        return $this->hasOne(PerusahaanProfile::class, 'user_id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(MahasiswaProfile::class, 'user_id');
    }
    
    public function mahasiswaProfile()
    {
        return $this->hasOne(MahasiswaProfile::class, 'user_id');
    }
    
    /**
     * Get all jobs posted by this user (company)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }
}