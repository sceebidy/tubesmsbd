<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['judul', 'isi', 'created_by', 'target_users'];

    /**
     * Cast target_users ke array secara otomatis.
     * Saat disimpan: array → JSON string di DB.
     * Saat dibaca : JSON string → array PHP.
     * Nilai null tetap null (artinya siaran ke semua pegawai).
     */
    protected $casts = [
        'target_users' => 'array',
    ];
}