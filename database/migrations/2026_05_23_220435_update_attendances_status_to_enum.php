<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateAttendancesStatusToEnum extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus default value terlebih dahulu
        DB::statement('ALTER TABLE attendances MODIFY status VARCHAR(255) NULL');
        
        // Ubah kolom status menjadi ENUM
        DB::statement("ALTER TABLE attendances MODIFY status ENUM('hadir', 'terlambat', 'izin', 'sakit', 'alpa') DEFAULT 'alpa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke VARCHAR
        DB::statement('ALTER TABLE attendances MODIFY status VARCHAR(255) NULL');
    }
}