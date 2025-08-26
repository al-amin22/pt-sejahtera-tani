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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['neraca', 'laba_rugi', 'arus_kas'])->index();
            $table->date('periode_mulai')->index();
            $table->date('periode_selesai')->index();
            $table->json('data'); // simpan hasil perhitungan laporan
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
