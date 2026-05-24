<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\KinerjaCleaning;
use App\Models\Pengajuan;
use App\Models\Attendance;
use Carbon\Carbon;

class CleaningKinerjaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        // ============================================================
        // CEK STATUS CHECK-IN HARI INI (TANPA REDIRECT)
        // ============================================================
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
        
        // CEK APAKAH SUDAH CHECK-IN
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
        
        // CEK APAKAH SUDAH PERNAH INPUT KINERJA HARI INI
        $sudahInputHariIni = KinerjaCleaning::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->exists();
        
        // Ambil riwayat kinerja hari ini
        $riwayatHariIni = KinerjaCleaning::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Total semua kinerja user
        $totalKinerja = KinerjaCleaning::where('user_id', $userId)->count();
        
        // Total area unik yang pernah dikerjakan hari ini
        $totalAreaHariIni = KinerjaCleaning::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->distinct('area')
            ->count('area');
        
        // Server time untuk clock
        $serverTime = now('Asia/Jakarta');
        
        return view('cleaning.kinerja', compact(
            'riwayatHariIni', 
            'totalKinerja', 
            'totalAreaHariIni',
            'isIzinHariIni',
            'izinStatus',
            'serverTime',
            'sudahInputHariIni',
            'sudahCheckIn'
        ));
    }

    public function store(Request $request)
    {
        try {
            $userId = Auth::id();
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            
            // ============================================================
            // VALIDASI 1: Cek apakah user sudah check-in hari ini
            // ============================================================
            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $today)
                ->first();
            
            $sudahCheckIn = $attendance && $attendance->check_in != null;
            
            if (!$sudahCheckIn) {
                return redirect()->back()->with('error', '❌ Anda harus CHECK-IN terlebih dahulu sebelum dapat menginput kinerja cleaning!');
            }
            
            // ============================================================
            // VALIDASI 2: Cek apakah user sedang izin/sakit hari ini
            // ============================================================
            $isIzinHariIni = Pengajuan::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->exists();
            
            if ($isIzinHariIni) {
                return redirect()->back()->with('error', 'Anda sedang IZIN/SAKIT hari ini. Tidak dapat menginput kinerja cleaning.');
            }
            
            // ============================================================
            // VALIDASI 3: Cek apakah sudah input kinerja hari ini
            // ============================================================
            $sudahInputHariIni = KinerjaCleaning::where('user_id', $userId)
                ->whereDate('tanggal', $today)
                ->exists();
            
            if ($sudahInputHariIni) {
                return redirect()->back()->with('error', 'Anda sudah menginput kinerja cleaning hari ini. Hanya diperbolehkan 1 kali input per hari.');
            }
            
            // ============================================================
            // VALIDASI 4: Validasi input form
            // ============================================================
            $request->validate([
                'area' => 'required|array|min:1',
                'area.*' => 'required|string|max:255',
                'keterangan' => 'required|array|min:1',
                'keterangan.*' => 'required|string|min:3',
                'foto' => 'required|array|min:1',
                'foto.*' => 'required|string',
            ]);
            
            $savedCount = 0;
            $errors = [];

            foreach ($request->foto as $index => $fotoBase64) {
                if (empty($fotoBase64)) {
                    $errors[] = "Foto ke-" . ($index + 1) . " tidak boleh kosong";
                    continue;
                }

                $area = $request->area[$index] ?? 'Area ' . ($index + 1);
                $keterangan = $request->keterangan[$index] ?? '';

                $imageData = $this->convertBase64ToImage($fotoBase64);
                
                if (!$imageData) {
                    $errors[] = "Foto ke-" . ($index + 1) . " tidak valid";
                    continue;
                }

                $fileName = 'kinerja-cleaning/' . date('Y/m/d') . '/' . uniqid() . '_' . time() . '.jpg';
                $saved = Storage::disk('public')->put($fileName, $imageData);
                
                if (!$saved) {
                    $errors[] = "Foto ke-" . ($index + 1) . " gagal disimpan";
                    continue;
                }

                try {
                    $kinerja = KinerjaCleaning::create([
                        'user_id' => $userId,
                        'area' => $area,
                        'keterangan' => $keterangan,
                        'foto' => $fileName,
                        'tanggal' => $today,
                    ]);
                    
                    if ($kinerja) {
                        $savedCount++;
                    } else {
                        $errors[] = "Data ke-" . ($index + 1) . " gagal disimpan ke database";
                    }
                    
                } catch (\Exception $dbError) {
                    $errors[] = "Data ke-" . ($index + 1) . " gagal disimpan: " . $dbError->getMessage();
                    if (Storage::disk('public')->exists($fileName)) {
                        Storage::disk('public')->delete($fileName);
                    }
                }
            }

            if ($savedCount === 0) {
                $errorMessage = 'Tidak ada data yang berhasil disimpan.';
                if (!empty($errors)) {
                    $errorMessage .= ' ' . implode(' ', $errors);
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            $message = $savedCount . ' data kinerja berhasil disimpan.';
            if (!empty($errors)) {
                $message .= ' Namun ada ' . count($errors) . ' data yang gagal: ' . implode(' ', $errors);
            }
            
            return redirect()->route('cleaning.kinerja')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Cleaning Kinerja Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function convertBase64ToImage($base64String)
    {
        if (empty($base64String)) {
            return null;
        }

        try {
            if (!str_contains($base64String, 'base64,')) {
                return null;
            }

            $parts = explode('base64,', $base64String);
            if (count($parts) < 2) {
                return null;
            }
            
            $imageData = base64_decode($parts[1]);
            
            if ($imageData === false || empty($imageData)) {
                return null;
            }
            
            return $imageData;
            
        } catch (\Exception $e) {
            return null;
        }
    }
}