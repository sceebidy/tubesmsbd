<?php
// app/Http/Controllers/LaporanPanenController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanPanen;
use App\Models\Pengajuan;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class LaporanPanenController extends Controller
{
    /**
     * Menampilkan halaman input panen untuk USER (pekerja sawit)
     */
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        // CEK APAKAH USER MEMILIKI MANDOR
        $hasMandor = !is_null(Auth::user()->mandor_id);
        
        // Cek sudah check in
        $sudahCheckIn = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->exists();
        
        // Cek izin/sakit (PRIORITAS UTAMA)
        $isIzinHariIni = Pengajuan::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->exists();
        
        $izinStatus = null;
        if ($isIzinHariIni) {
            $pengajuan = Pengajuan::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->first();
            $izinStatus = $pengajuan->jenis;
        }
        
        // Data lainnya
        $laporanHariIni = LaporanPanen::where('pekerja_id', $userId)
            ->whereDate('tanggal', $today)
            ->first();
        
        $mandorSudahVerifikasi = false;
        if(Auth::user()->mandor_id) {
            $mandorSudahVerifikasi = LaporanPanen::where('mandor_id', Auth::user()->mandor_id)
                ->whereDate('tanggal', $today)
                ->where('status', 'diverifikasi_mandor')
                ->exists();
        }
        
        $riwayatPanen = LaporanPanen::where('pekerja_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('user.laporan-panen', compact(
            'laporanHariIni',
            'riwayatPanen',
            'isIzinHariIni',
            'izinStatus',
            'mandorSudahVerifikasi',
            'sudahCheckIn',
            'hasMandor'
        ));
    }
    
    /**
     * Menampilkan halaman laporan panen untuk MANDOR
     */
    public function mandorIndex()
    {
        $userId = Auth::id();
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        // ============================================================
        // CEK STATUS CHECK-IN HARI INI (TANPA REDIRECT)
        // ============================================================
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
        
        $sudahCheckIn = $attendance && $attendance->check_in != null;
        
        // CEK IZIN/SAKIT HARI INI
        $isIzinHariIni = Pengajuan::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->exists();
        
        $izinStatus = null;
        if ($isIzinHariIni) {
            $pengajuan = Pengajuan::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->first();
            $izinStatus = $pengajuan->jenis;
        }
        
        // CEK SUDAH VERIFIKASI HARI INI
        $sudahVerifikasiHariIni = LaporanPanen::where('mandor_id', $userId)
            ->whereDate('tanggal', $today)
            ->where('status', 'diverifikasi_mandor')
            ->exists();
        
        // AMBIL SEMUA PEKERJA YANG DIBAWAH MANDOR INI
        $pekerjaIds = User::where('mandor_id', $userId)
            ->where('role', 'user')
            ->pluck('id')
            ->toArray();
        
        // Jika tidak ada pekerja, tampilkan array kosong
        if (empty($pekerjaIds)) {
            $laporanGroup = collect([]);
            return view('mandor.laporan-panen', compact(
                'laporanGroup', 
                'today', 
                'isIzinHariIni',
                'izinStatus',
                'sudahCheckIn',
                'sudahVerifikasiHariIni'
            ));
        }
        
        // AMBIL LAPORAN PANEN PER TANGGAL
        $laporanGroup = LaporanPanen::whereIn('pekerja_id', $pekerjaIds)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy('tanggal');
        
        return view('mandor.laporan-panen', compact(
            'laporanGroup', 
            'today', 
            'isIzinHariIni',
            'izinStatus',
            'sudahCheckIn',
            'sudahVerifikasiHariIni'
        ));
    }
    
    /**
     * Verifikasi panen oleh mandor
     */
    public function verifikasiMandor(Request $request, $tanggal)
    {
        try {
            $userId = Auth::id();
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            $tanggalParsed = Carbon::parse($tanggal)->toDateString();
            
            // ============================================================
            // VALIDASI 1: CEK CHECK-IN
            // ============================================================
            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $today)
                ->first();
            
            $sudahCheckIn = $attendance && $attendance->check_in != null;
            
            if (!$sudahCheckIn) {
                return redirect()->route('mandor.panen')
                    ->with('error', ' ANDA BELUM CHECK IN! <br><br>Anda harus check in terlebih dahulu sebelum dapat memverifikasi panen.');
            }
            
            // ============================================================
            // VALIDASI 2: CEK IZIN/SAKIT
            // ============================================================
            $isIzinHariIni = Pengajuan::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->exists();
            
            if ($isIzinHariIni) {
                return redirect()->route('mandor.panen')
                    ->with('error', ' Anda sedang IZIN/SAKIT hari ini. Tidak dapat memverifikasi panen.');
            }
            
            // ============================================================
            // VALIDASI 3: CEK SUDAH VERIFIKASI HARI INI
            // ============================================================
            $sudahVerifikasiHariIni = LaporanPanen::where('mandor_id', $userId)
                ->whereDate('tanggal', $today)
                ->where('status', 'diverifikasi_mandor')
                ->exists();
            
            if ($sudahVerifikasiHariIni) {
                return redirect()->route('mandor.panen')
                    ->with('error', ' Anda sudah melakukan verifikasi panen hari ini. Hanya diperbolehkan 1 kali verifikasi per hari.');
            }
            
            // ============================================================
            // VALIDASI 4: Validasi input
            // ============================================================
            $request->validate([
                'total_berat_kg' => 'required|numeric|min:0.1',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            // Update semua laporan panen pada tanggal tersebut
            $updated = LaporanPanen::where('mandor_id', $userId)
                ->whereDate('tanggal', $tanggalParsed)
                ->update([
                    'status' => 'diverifikasi_mandor',
                    'total_berat_kg' => $request->total_berat_kg,
                    'catatan' => $request->catatan,
                ]);
            
            if ($updated) {
                return redirect()->route('mandor.panen')
                    ->with('success', ' Laporan panen berhasil diverifikasi!');
            }
            
            return redirect()->route('mandor.panen')
                ->with('error', ' Gagal memverifikasi laporan panen.');
                
        } catch (\Exception $e) {
            \Log::error('Verifikasi Mandor Error: ' . $e->getMessage());
            return redirect()->route('mandor.panen')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Menyimpan data panen untuk USER (pekerja sawit)
     */
    public function store(Request $request)
    {
        try {
            $userId = Auth::id();
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            
            // ============================================================
            // VALIDASI 0: CEK APAKAH USER MEMILIKI MANDOR
            // ============================================================
            if (is_null(Auth::user()->mandor_id)) {
                return redirect()->route('user.panen')
                    ->with('error', ' ANDA BELUM MEMILIKI MANDOR! <br><br>' .
                        'Anda tidak dapat menginput panen karena belum memiliki mandor.<br><br>' .
                        'Silakan hubungi administrator untuk ditugaskan ke mandor terlebih dahulu.');
            }
            
            // ============================================================
            // VALIDASI 1: CEK APAKAH SEDANG IZIN/SAKIT
            // ============================================================
            $isIzinHariIni = Pengajuan::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->exists();
            
            if ($isIzinHariIni) {
                return redirect()->route('user.panen')
                    ->with('error', ' Anda sedang IZIN/SAKIT hari ini. Tidak dapat menginput panen.');
            }
            
            // ============================================================
            // VALIDASI 2: CEK APAKAH SUDAH CHECK IN HARI INI
            // ============================================================
            $sudahCheckIn = Attendance::where('user_id', $userId)
                ->whereDate('date', $today)
                ->whereNotNull('check_in')
                ->exists();
            
            if (!$sudahCheckIn) {
                return redirect()->route('user.panen')
                    ->with('error', ' ANDA BELUM CHECK IN! <br><br>Anda harus check in terlebih dahulu sebelum menginput panen.');
            }
            
            // ============================================================
            // VALIDASI 3: CEK APAKAH SUDAH INPUT PANEN HARI INI
            // ============================================================
            $sudahInputHariIni = LaporanPanen::where('pekerja_id', $userId)
                ->whereDate('tanggal', $today)
                ->exists();
            
            if ($sudahInputHariIni) {
                return redirect()->route('user.panen')
                    ->with('error', ' Anda sudah menginput panen hari ini. Hanya diperbolehkan 1 kali input per hari.');
            }
            
            // ============================================================
            // VALIDASI 4: CEK APAKAH MANDOR SUDAH VERIFIKASI
            // ============================================================
            $mandorSudahVerifikasi = false;
            if(Auth::user()->mandor_id) {
                $mandorSudahVerifikasi = LaporanPanen::where('mandor_id', Auth::user()->mandor_id)
                    ->whereDate('tanggal', $today)
                    ->where('status', 'diverifikasi_mandor')
                    ->exists();
            }
            
            if ($mandorSudahVerifikasi) {
                return redirect()->route('user.panen')
                    ->with('error', ' Mandor Anda sudah memverifikasi laporan panen hari ini. Tidak dapat menginput panen setelah verifikasi.');
            }
            
            // ============================================================
            // VALIDASI FORM
            // ============================================================
            $request->validate([
                'brondolan_kg' => 'required|numeric|min:0.1',
                'janjang' => 'required|integer|min:1',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            // Simpan data
            LaporanPanen::create([
                'pekerja_id' => $userId,
                'tanggal' => $today,
                'brondolan_kg' => $request->brondolan_kg,
                'janjang' => $request->janjang,
                'catatan' => $request->catatan,
                'status' => 'input_pekerja',
                'mandor_id' => Auth::user()->mandor_id,
            ]);
            
            return redirect()->route('user.panen')
                ->with('success', ' Data panen berhasil disimpan!');
                
        } catch (\Exception $e) {
            \Log::error('Store Panen Error: ' . $e->getMessage());
            return redirect()->route('user.panen')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}