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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_peminjaman')->unique();
            $table->string('nama_pengguna');
            $table->string('nip');
            $table->string('unit');
            $table->string('jabatan');
            $table->string('tujuan_keperluan');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('peminjaman_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->foreignId('peralatan_id')->constrained('peralatan')->cascadeOnDelete();
            $table->unique(['peminjaman_id', 'peralatan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_detail');
        Schema::dropIfExists('peminjaman');
    }
};
