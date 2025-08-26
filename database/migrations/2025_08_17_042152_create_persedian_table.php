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
        Schema::create('persedian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id')->index(); // tanpa FK
            $table->integer('jumlah')->default(0);
            $table->timestamps();
            $table->index(['produk_id', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persedian');
    }
};
