<?php
// app/Http/Controllers/PengajuanController.php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $riwayatPengajuan = Pengajuan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $pengajuanPending = Pengajuan::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
        
        return view('pengajuan.index', compact('riwayatPengajuan', 'pengajuanPending'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'jenis' => 'required|in:izin,sakit',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        
        // Validasi maksimal 30 hari
        if ($tanggalMulai->diffInDays($tanggalSelesai) > 30) {
            return back()->with('error', 'Maksimal pengajuan adalah 30 hari');
        }
        
        // ============================================================
        // VALIDASI 1: CEK APAKAH SUDAH CHECK IN DI RENTANG TANGGAL
        // ============================================================
        $sudahCheckIn = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$tanggalMulai, $tanggalSelesai])
            ->whereNotNull('check_in')
            ->exists();
        
        if ($sudahCheckIn) {
            return back()->with('error', 
                '❌ TIDAK BISA MENGAJUKAN IZIN/SAKIT! ❌' . "\n\n" .
                'Anda sudah melakukan check in di salah satu tanggal yang diajukan. ' .
                'Pengajuan izin/sakit hanya bisa dilakukan untuk tanggal yang belum Anda check in. ' .
                'Silakan pilih tanggal lain yang belum Anda check in.'
            );
        }
        
        // ============================================================
        // VALIDASI 2: CEK APAKAH SUDAH ADA ABSENSI (HADIR/TERLAMBAT/ALPA)
        // ============================================================
        $sudahAbsen = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$tanggalMulai, $tanggalSelesai])
            ->whereIn('status', ['hadir', 'terlambat', 'alpa'])
            ->exists();
        
        if ($sudahAbsen) {
            return back()->with('error', 
                '❌ TIDAK BISA MENGAJUKAN IZIN/SAKIT! ❌' . "\n\n" .
                'Anda sudah memiliki absensi (hadir/terlambat/alpa) di salah satu tanggal yang diajukan. ' .
                'Pengajuan izin/sakit hanya bisa dilakukan untuk tanggal yang belum Anda absen. ' .
                'Silakan pilih tanggal lain yang belum Anda absen.'
            );
        }
        
        // ============================================================
        // VALIDASI 3: CEK OVERLAP DENGAN PENGAJUAN LAIN
        // ============================================================
        $overlap = Pengajuan::where('user_id', $user->id)
            ->where(function($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                      ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                      ->orWhere(function($q) use ($tanggalMulai, $tanggalSelesai) {
                          $q->where('tanggal_mulai', '<=', $tanggalMulai)
                            ->where('tanggal_selesai', '>=', $tanggalSelesai);
                      });
            })
            ->exists();
        
        if ($overlap) {
            return back()->with('error', 'Tanggal pengajuan bentrok dengan pengajuan yang sudah ada');
        }
        
        // Upload lampiran
        $attachmentPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = 'pengajuan_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $attachmentPath = $file->storeAs('pengajuan', $fileName, 'public');
        }
        
        Pengajuan::create([
            'user_id' => $user->id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'alasan' => $request->alasan,
            'lampiran' => $attachmentPath,
            'status' => 'pending',
        ]);
        
        $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
        $pesan = $request->jenis == 'izin' 
            ? "Pengajuan izin untuk {$jumlahHari} hari berhasil dikirim"
            : "Pengajuan sakit untuk {$jumlahHari} hari berhasil dikirim";
        
        return redirect()->route('pengajuan.index')->with('success', $pesan);
    }
    
    public function batal($id)
    {
        $pengajuan = Pengajuan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();
        
        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan atau sudah diproses');
        }
        
        if ($pengajuan->lampiran) {
            Storage::disk('public')->delete($pengajuan->lampiran);
        }
        
        $pengajuan->delete();
        
        return back()->with('success', 'Pengajuan berhasil dibatalkan');
    }
    
    public function detail($id)
    {
        $pengajuan = Pengajuan::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        return view('pengajuan.detail', compact('pengajuan'));
    }
}