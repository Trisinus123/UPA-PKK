<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    //table_name artikels
    protected $table = 'artikels';

    //attributs judul_artikel, deskripsi, gambar
    protected $fillable = [
        'judul_artikel',
        'deskripsi',
        'gambar',
    ];

    
}
