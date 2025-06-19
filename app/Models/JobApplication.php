<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'student_id',
        'status'
    ];

        public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'application_id');
    }



    // job
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }


    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
