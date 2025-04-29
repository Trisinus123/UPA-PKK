<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_id');
                $table->unsignedBigInteger('student_id');
                $table->string('resume_path')->nullable();
                $table->enum('status', ['pending', 'reviewing', 'interview','rejected', 'accepted'])->default('pending'); // <-- langsung enum baru
                $table->timestamps();

                // Foreign keys bisa diaktifkan kalau butuh
                // $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
                // $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};
