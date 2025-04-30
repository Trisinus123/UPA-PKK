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
        'address',
        'website',
        'description',
        'logo'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}