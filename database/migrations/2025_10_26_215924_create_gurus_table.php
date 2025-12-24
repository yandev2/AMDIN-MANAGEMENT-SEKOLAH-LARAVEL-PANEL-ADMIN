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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_sekolah')->constrained('sekolahs')->cascadeOnDelete();
            $table->foreignId('id_shift')->nullable()->constrained('shifts')->nullOnDelete();
            $table->foreignId('id_jabatan')->nullable()->constrained('jabatans')->nullOnDelete();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('foto')->nullable()->default(null);
            $table->string('nip')->nullable()->default(null);
            $table->enum('jk', ['L', 'P'])->nullable();
            $table->longText('alamat')->nullable()->default(null);
            $table->enum('status_dinas', ['dinas dalam', 'dinas luar']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('no_hp')->nullable()->default(null);
            $table->string('agama')->nullable()->default(null);
            $table->text('pendidikan_terakhir')->nullable()->default(null);
            $table->text('tempat_lahir')->nullable()->default(null);
            $table->date('tanggal_lahir')->nullable()->default(null);
            $table->string('face_id')->nullable()->default(null);
            $table->string('auth_token')->nullable()->default(null);
            $table->string('id_device')->nullable()->default(null);
            $table->index('id_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
