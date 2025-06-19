<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDocumentRequirement extends Model
{
    use HasFactory;


    //tabel name = job_document_requirements

    protected $table = 'job_documents_requirements';

    protected $fillable = [
        'job_id',
        'document_name',
        'is_required',
        'description',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
