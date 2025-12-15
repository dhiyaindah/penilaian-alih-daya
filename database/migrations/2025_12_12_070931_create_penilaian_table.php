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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();

            // Relasi foreign key
            $table->unsignedBigInteger('alih_daya_id');   // yang dinilai
            $table->unsignedBigInteger('pegawai_id');     // penilai

            // Isi penilaian
            $table->float('skor')->default(0);
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Set foreign key (opsional, tapi disarankan)
            $table->foreign('alih_daya_id')->references('id')->on('tim_alih_daya')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
