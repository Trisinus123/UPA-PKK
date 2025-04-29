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
        if (!Schema::hasTable('perusahaan_profiles')) {
            Schema::create('perusahaan_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('company_name')->nullable(); 
                $table->text('alamat')->nullable();        // sebelumnya address
                $table->string('industry')->nullable();
                $table->string('website')->nullable();
                $table->text('deskripsi')->nullable();     // sebelumnya description
                $table->string('foto')->nullable();         // sebelumnya logo
                $table->timestamps();
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
        Schema::dropIfExists('perusahaan_profiles');
    }
};
