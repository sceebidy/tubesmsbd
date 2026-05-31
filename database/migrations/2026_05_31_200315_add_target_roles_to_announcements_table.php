<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // NULL  = tidak ada filter role (berlaku untuk semua / sudah difilter target_users)
            // array = hanya pegawai yang role-nya ada di array ini
            // Contoh nilai: ["mandor", "security", "cleaning", "kantoran", "user"]
            $table->json('target_roles')->nullable()->after('target_users');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('target_roles');
        });
    }
};