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
        Schema::create('presensi_gurus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_sekolah')->constrained('sekolahs')->cascadeOnDelete();
            $table->foreignId('id_guru')->constrained('gurus')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable()->default(null);
            $table->time('jam_keluar')->nullable()->default(null);
            $table->enum('absen_masuk', ['H', 'I', 'S']);
            $table->string('lokasi_masuk')->nullable()->default(null);
            $table->enum('absen_keluar', ['H', 'I', 'S'])->nullable()->default(null);
            $table->string('lokasi_keluar')->nullable()->default(null);
            $table->time('durasi_kerja')->nullable()->default(null);
            $table->string('dokumen')->nullable()->default(null);
            $table->longText('keterangan')->nullable()->default(null);
            $table->string('face')->nullable()->default(null);
            $table->enum('status', ['terlambat', 'tepat waktu', 'Izin', 'Sakit'])->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_gurus');
    }
};
