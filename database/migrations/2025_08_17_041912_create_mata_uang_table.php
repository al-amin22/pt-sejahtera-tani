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
        Schema::create('mata_uang', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique(); // IDR, USD, CNY
            $table->string('nama', 100);
            $table->decimal('kurs', 18, 6)->default(1); // kurs terhadap mata uang dasar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_uang');
    }
};
