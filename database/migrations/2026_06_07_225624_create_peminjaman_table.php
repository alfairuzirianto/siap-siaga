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
            $table->foreignId('pengguna_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tujuan_keperluan');
            $table->date('tgl_rencana_pinjam');
            $table->date('tgl_rencana_kembali');
            $table->string('status');
            $table->date('tgl_realisasi_kembali')->nullable();
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('keterangan_pinjam')->nullable();
            $table->string('keterangan_kembali')->nullable();
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
