<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'no_hp',
        'role',
        'password',
        'mandor_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi: User ini memiliki mandor
    public function mandor()
    {
        return $this->belongsTo(User::class, 'mandor_id');
    }

    // Relasi: User ini menjadi mandor bagi banyak pekerja
    public function pekerja()
    {
        return $this->hasMany(User::class, 'mandor_id');
    }

    // ======================================================================
    // TAMBAHAN RELASI UNTUK PENGAJUAN IZIN/SAKIT
    // ======================================================================
    
    /**
     * Relasi ke tabel pengajuan (semua pengajuan user)
     */
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }
    
    /**
     * Relasi ke pengajuan yang masih pending
     */
    public function pengajuanPending()
    {
        return $this->hasMany(Pengajuan::class)->where('status', 'pending');
    }
    
    /**
     * Relasi ke pengajuan yang sudah disetujui
     */
    public function pengajuanDisetujui()
    {
        return $this->hasMany(Pengajuan::class)->where('status', 'disetujui');
    }
    
    /**
     * Relasi ke pengajuan yang ditolak
     */
    public function pengajuanDitolak()
    {
        return $this->hasMany(Pengajuan::class)->where('status', 'ditolak');
    }
    
    /**
     * Relasi ke pengajuan untuk role admin (yang melakukan approval)
     */
    public function approvals()
    {
        return $this->hasMany(Pengajuan::class, 'approved_by');
    }

    // Helper methods untuk role
    public function isMandor()
    {
        return $this->role === 'mandor';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isSecurity()
    {
        return $this->role === 'security';
    }

    public function isCleaning()
    {
        return $this->role === 'cleaning';
    }

    public function isKantoran()
    {
        return $this->role === 'kantoran';
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function isManager()
    {
        return $this->role === 'manager';
    }
}