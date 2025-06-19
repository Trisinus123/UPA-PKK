<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    use HasFactory;

    // Tabel name = application_documents

    protected $table = 'application_documents';

    protected $fillable = [
        'application_id',
        'document_requirement_id',
        'file_path',
    ];

        public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }

    public function requirement()
    {
        return $this->belongsTo(JobDocumentRequirement::class, 'document_requirement_id');
    }
}
