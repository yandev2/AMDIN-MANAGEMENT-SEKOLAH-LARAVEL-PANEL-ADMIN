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
        Schema::create('presensi_siswas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_sekolah')->constrained('sekolahs')->cascadeOnDelete();
            $table->foreignId('id_siswa')->constrained('siswas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('bulan')->nullable();
            $table->string('tahun_ajaran');
            $table->enum('status', ['H', 'S', 'I', 'A']);
            $table->string('keterangan')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_siswas');
    }
};
