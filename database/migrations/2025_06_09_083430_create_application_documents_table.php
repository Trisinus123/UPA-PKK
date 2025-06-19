<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan nama tabel benar dan belum ada
        if (!Schema::hasTable('application_documents')) {
            Schema::create('application_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('application_id');
                $table->unsignedBigInteger('document_requirement_id');
                $table->string('file_path');
                $table->timestamps();

                // Foreign key untuk job_applications
                $table->foreign('application_id')
                      ->references('id')
                      ->on('job_applications')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');

                // Foreign key untuk job_document_requirements
                $table->foreign('document_requirement_id')
                      ->references('id')
                      ->on('job_document_requirements')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');

                // Index untuk performa query
                $table->index(['application_id', 'document_requirement_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
