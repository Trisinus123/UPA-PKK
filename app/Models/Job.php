<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'category_id',
        'title',
        'category',
        'description',
        'location',
        'salary_min',
        'salary_max',
        'requirements',
        'status',
        'deadline',
        'gambar'
    ];

    // Relasi ke persyaratan dokumen pekerjaan
    public function documentRequirements()
    {
        return $this->hasMany(JobDocumentRequirement::class);
    }

    // Relasi ke kategori pekerjaan
    public function categoryJob()
    {
        return $this->belongsTo(CategoryJob::class, 'category_id');
    }

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // Relasi ke perusahaan (user)
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    // Relasi duplikat, sebaiknya dihapus jika tidak digunakan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke lamaran pekerjaan
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Format rentang gaji dalam format yang mudah dibaca
    public function getFormattedSalaryRangeAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return 'Rp ' . number_format($this->salary_min, 0, ',', '.') . ' - Rp ' . number_format($this->salary_max, 0, ',', '.');
        } elseif ($this->salary_min) {
            return 'Mulai dari Rp ' . number_format($this->salary_min, 0, ',', '.');
        } elseif ($this->salary_max) {
            return 'Hingga Rp ' . number_format($this->salary_max, 0, ',', '.');
        } else {
            return 'Dapat dinegosiasikan';
        }
    }

    // Cek apakah mahasiswa sudah melamar pekerjaan ini
    public function hasApplied($studentId)
    {
        return $this->applications()->where('student_id', $studentId)->exists();
    }

    // Ambil kategori pekerjaan yang tersedia
    public static function getCategories()
    {
        return self::CATEGORIES;
    }

    // Ambil nama kategori berdasarkan key-nya
    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    // Scope untuk filter pekerjaan yang berhubungan dengan IT
    public function scopeItRelated($query)
    {
        return $query->whereIn('category', array_keys(self::CATEGORIES));
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
