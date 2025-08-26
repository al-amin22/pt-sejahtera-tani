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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_transaksi')->index();
            $table->unsignedBigInteger('transaksi_id')->index(); // tanpa FK
            $table->string('nama_barang'); // tanpa FK
            $table->decimal('harga', 20, 2);
            $table->enum('jenis', ['pengeluaran', 'pemasukan'])->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('mata_uang_id')->index();
            $table->string('satuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->decimal('subtotal', 20, 2)->nullable();
            $table->string('referensi')->nullable();
            $table->unsignedBigInteger('dari_rekening_id')->nullable()->index();
            $table->unsignedBigInteger('ke_rekening_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
