<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'salary_min',
        'salary_max',
        'requirements',
        'status',
        'deadline'
    ];
    
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
    
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
    
    public function getFormattedSalaryRangeAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return 'Rp ' . number_format($this->salary_min, 0, ',', '.') . ' - Rp ' . number_format($this->salary_max, 0, ',', '.');
        } elseif ($this->salary_min) {
            return 'From Rp ' . number_format($this->salary_min, 0, ',', '.');
        } elseif ($this->salary_max) {
            return 'Up to Rp ' . number_format($this->salary_max, 0, ',', '.');
        } else {
            return 'Negotiable';
        }
    }
    
    public function hasApplied($studentId)
    {
        return $this->applications()->where('student_id', $studentId)->exists();
    }
}
