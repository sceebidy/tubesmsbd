<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'no_hp',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Override method untuk mencari user berdasarkan no_hp
     */
    public function findForPassport($identifier)
    {
        return $this->where('no_hp', $identifier)->first();
    }

    /**
     * Override username method untuk menggunakan no_hp
     */
    public function username()
    {
        return 'no_hp';
    }

    /**
     * Relasi dengan attendances
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    /**
     * Relasi dengan catatan_panen
     */
    public function catatanPanen()
    {
        return $this->hasMany(CatatanPanen::class, 'id_pegawai');
    }

    /**
     * Relasi dengan rapot
     */
    public function rapot()
    {
        return $this->hasMany(Rapot::class, 'id_user');
    }

    /**
     * Relasi dengan announcements (pengumuman yang dibuat)
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }
}