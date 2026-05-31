<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['judul', 'isi', 'created_by', 'target_users', 'target_roles'];

    /**
     * target_users : null = semua pegawai | array = hanya ID pegawai tertentu
     * target_roles  : null = tidak ada filter role | array = hanya role tertentu
     *
     * Logika penggabungan (di controller):
     *  - Jika target_users terisi  → tampilkan ke pegawai berdasarkan ID spesifik
     *  - Jika target_roles terisi  → tampilkan ke pegawai berdasarkan role
     *  - Jika keduanya null        → siaran umum (semua pegawai)
     */
    protected $casts = [
        'target_users' => 'array',
        'target_roles' => 'array',
    ];
}