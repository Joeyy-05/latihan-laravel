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
        Schema::create('catatan_keuangans', function (Blueprint $table) {
            $table->id();
            
            // Kolom wajib untuk menghubungkan ke pengguna
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom untuk data catatan keuangan
            $table->string('judul');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2); // Presisi untuk nilai uang
            $table->date('tanggal'); // Tanggal transaksi
            
            // Kolom opsional
            $table->text('keterangan')->nullable(); // Untuk Trix editor (Opsional)
            $table->string('gambar')->nullable(); // Untuk upload bukti/gambar
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_keuangans');
    }
};