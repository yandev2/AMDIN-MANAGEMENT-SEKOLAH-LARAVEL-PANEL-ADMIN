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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_sekolah')->constrained('sekolahs')->cascadeOnDelete();
            $table->foreignId('id_kelas')->nullable()->constrained('kelas')->nullOnDelete();
            $table->string('nis');
            $table->string('nisn');
            $table->text('nama_siswa');
            $table->enum('jk', ['L', 'P']);
            $table->longText('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('agama');
            $table->longText('alamat');
            $table->year('tahun_masuk');
            $table->string('foto')->nullable()->default(null);
            $table->string('nik')->nullable()->default(null);
            $table->string('no_kk')->nullable()->default(null);
            $table->string('status')->nullable()->default('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
