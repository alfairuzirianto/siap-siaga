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
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pemeliharaan')->unique();
            $table->foreignId('peralatan_id')->constrained('peralatan')->cascadeOnDelete();
            $table->string('nama_petugas')->nullable();
            $table->string('jenis_pemeliharaan');
            $table->date('tanggal_pemeliharaan');
            $table->decimal('biaya', 15, 2)->nullable();
            $table->string('deskripsi');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan');
    }
};
