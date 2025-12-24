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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_sekolah')->constrained('sekolahs')->cascadeOnDelete();
            $table->foreignId('id_guru')->nullable()->constrained('gurus')->noActionOnDelete();
            $table->integer('tingkat');
            $table->text('nama_kelas');
            $table->longText('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
