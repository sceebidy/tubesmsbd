<?php
// app/Models/Pengajuan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    
    protected $fillable = [
        'user_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'lampiran',
        'status',
        'catatan_admin',
        'approved_by',
        'approved_at',
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'approved_at' => 'datetime',
    ];
    
    const JENIS_IZIN = 'izin';
    const JENIS_SAKIT = 'sakit';
    
    const STATUS_PENDING = 'pending';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function getJumlahHariAttribute()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return 0;
        }
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
    
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-700',
            self::STATUS_DISETUJUI => 'bg-green-100 text-green-700',
            self::STATUS_DITOLAK => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
    
    public function getJenisBadgeClass()
    {
        return match($this->jenis) {
            self::JENIS_IZIN => 'bg-blue-100 text-blue-700',
            self::JENIS_SAKIT => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
    
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    public function isApproved()
    {
        return $this->status === self::STATUS_DISETUJUI;
    }
    
    public function isRejected()
    {
        return $this->status === self::STATUS_DITOLAK;
    }
    
    // ==================================================
    // METHOD UNTUK AUTO CREATE ATTENDANCE
    // ==================================================
    
    /**
     * Membuat record absensi otomatis untuk semua tanggal dalam range pengajuan
     */
    public function createAttendanceRecords()
    {
        $start = Carbon::parse($this->tanggal_mulai)->startOfDay();
        $end = Carbon::parse($this->tanggal_selesai)->endOfDay();
        $currentDate = clone $start;
        $createdCount = 0;
        
        while ($currentDate <= $end) {
            // Cek apakah sudah ada absensi di tanggal tersebut
            $existing = Attendance::where('user_id', $this->user_id)
                ->whereDate('date', $currentDate)
                ->first();
            
            if (!$existing) {
                // Buat absensi baru
                Attendance::create([
                    'user_id' => $this->user_id,
                    'date' => $currentDate->toDateString(),
                    'status' => $this->jenis,
                    'check_in' => null,
                    'check_out' => null,
                    'note' => $this->jenis == 'izin' ? 'Izin (disetujui)' : 'Sakit (disetujui)',
                ]);
                $createdCount++;
            } else {
                // Update absensi yang sudah ada, tapi jangan overwrite jika sudah ada check_in
                if (!$existing->check_in) {
                    $existing->update([
                        'status' => $this->jenis,
                        'note' => $this->jenis == 'izin' ? 'Izin (disetujui)' : 'Sakit (disetujui)',
                    ]);
                    $createdCount++;
                }
            }
            
            $currentDate->addDay();
        }
        
        return $createdCount;
    }
    
    /**
     * Menghapus record absensi yang terkait dengan pengajuan ini
     */
    public function deleteAttendanceRecords()
    {
        $start = Carbon::parse($this->tanggal_mulai)->startOfDay();
        $end = Carbon::parse($this->tanggal_selesai)->endOfDay();
        
        return Attendance::where('user_id', $this->user_id)
            ->whereBetween('date', [$start, $end])
            ->whereNull('check_in') // Hanya hapus yang belum check in
            ->delete();
    }
    
    /**
     * Cek apakah sudah ada absensi di range tanggal tertentu
     */
    public function hasExistingAttendance()
    {
        $start = Carbon::parse($this->tanggal_mulai)->startOfDay();
        $end = Carbon::parse($this->tanggal_selesai)->endOfDay();
        
        return Attendance::where('user_id', $this->user_id)
            ->whereBetween('date', [$start, $end])
            ->exists();
    }
    
    /**
     * Cek apakah ada absensi yang sudah check in di range tanggal tertentu
     */
    public function hasCheckedInAttendance()
    {
        $start = Carbon::parse($this->tanggal_mulai)->startOfDay();
        $end = Carbon::parse($this->tanggal_selesai)->endOfDay();
        
        return Attendance::where('user_id', $this->user_id)
            ->whereBetween('date', [$start, $end])
            ->whereNotNull('check_in')
            ->exists();
    }
    
    /**
     * Mendapatkan tanggal-tanggal dalam range pengajuan
     */
    public function getDatesInRange()
    {
        $dates = [];
        $start = Carbon::parse($this->tanggal_mulai);
        $end = Carbon::parse($this->tanggal_selesai);
        $current = clone $start;
        
        while ($current <= $end) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }
        
        return $dates;
    }
    
    /**
     * Scope untuk pengajuan aktif (belum lewat)
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI)
            ->whereDate('tanggal_selesai', '>=', Carbon::today());
    }
    
    /**
     * Scope berdasarkan jenis
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
    
    /**
     * Scope berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}