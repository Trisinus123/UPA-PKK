<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_documents_requirements', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('job_id')
                  ->constrained('jobs')
                  ->onDelete('cascade'); // FOREIGN KEY job_id → jobs(id) ON DELETE CASCADE
            $table->string('document_name'); // VARCHAR(255) NOT NULL
            $table->boolean('is_required')->default(true); // BOOLEAN NOT NULL DEFAULT TRUE
            $table->string('description')->nullable(); // VARCHAR(255) NULLABLE
            $table->timestamp('created_at')->nullable(); // TIMESTAMP NULL DEFAULT NULL
            $table->timestamp('updated_at')->nullable(); // TIMESTAMP NULL DEFAULT NULL
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_documents_requirements');
    }
};
