<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perusahaan_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('alamat_perusahaan')->nullable();
            $table->string('website')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('status_perusahaan')->default(false); // 0 = tidak aktif, 1 = aktif
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perusahaan_profiles');
    }
};
