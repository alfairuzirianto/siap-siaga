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
        Schema::create('peralatan_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis');
            $table->timestamps();
        });

        Schema::create('peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_seri')->unique();
            $table->foreignId('peralatan_jenis_id')->constrained('peralatan_jenis')->cascadeOnDelete();
            $table->decimal('kapasitas', 5, 2)->nullable();
            $table->string('satuan')->nullable();
            $table->string('lokasi');
            $table->string('foto')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('peralatan');
        Schema::dropIfExists('peralatan_jenis');
    }
};
