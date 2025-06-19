<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryJob extends Model
{
    use HasFactory;

    // tabel_name = category

    protected $table = 'category';

    // nama_category

    protected $fillable = [
        'nama_category',
    ];
}
