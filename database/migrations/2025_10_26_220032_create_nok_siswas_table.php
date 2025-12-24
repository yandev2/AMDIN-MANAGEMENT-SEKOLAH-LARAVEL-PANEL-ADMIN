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
        Schema::create('nok_siswas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_sekolah')->constrained('sekolahs')->cascadeOnDelete();
            $table->foreignId('id_siswa')->nullable()->constrained('siswas')->cascadeOnDelete();
            $table->text('hubungan')->nullable()->default(null);
            $table->text('nama')->nullable()->default(null);
            $table->string('no_tlp')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nok_siswas');
    }
};
