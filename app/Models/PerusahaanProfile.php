<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerusahaanProfile extends Model
{
    use HasFactory;

    protected $table = 'perusahaan_profiles';

    protected $fillable = [
        'user_id',
        'website',
        'deskripsi',
        'foto',
        'alamat_perusahaan',
        'status_perusahaan'
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // Explicitly specify the foreign key
}

// In User model
public function perusahaanProfile()
{
    return $this->hasOne(PerusahaanProfile::class);
}
}
