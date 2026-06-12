<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->string('nomor_ba')->unique();
            $table->string('jenis_ba');
            $table->uuid('token')->unique();
            $table->boolean('is_valid')->default(true);
            $table->timestamps();
        });

        Schema::create('berita_acara_dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_id')->constrained('berita_acara')->cascadeOnDelete();
            $table->string('keterangan');
            $table->json('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_dokumentasi');
        Schema::dropIfExists('berita_acara');
    }
};
