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
        Schema::create('laporan_panen', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELASI USER
            |--------------------------------------------------------------------------
            */

            // Mandor
            $table->foreignId('mandor_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Pekerja sawit
            $table->foreignId('pekerja_id')
                ->constrained('users')
                ->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */

            $table->date('tanggal');

            /*
            |--------------------------------------------------------------------------
            | INPUT PEKERJA
            |--------------------------------------------------------------------------
            */

            // Berat brondolan pekerja
            $table->decimal('brondolan_kg', 10, 2)
                ->default(0);

            // Jumlah janjang pekerja
            $table->integer('janjang')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | INPUT MANDOR
            |--------------------------------------------------------------------------
            */

            // Total tandan seluruh tim
            $table->integer('total_tandan')
                ->nullable();

            // Total berat seluruh tim
            $table->decimal('total_berat_kg', 10, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | CATATAN
            |--------------------------------------------------------------------------
            */

            $table->text('catatan')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'input_pekerja',
                'diverifikasi_mandor',
                'selesai'
            ])->default('input_pekerja');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_panen');
    }
};