<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PatroliSecurity;
use App\Models\Pengajuan;
use App\Models\Attendance; // Tambahkan model Attendance
use Carbon\Carbon;

class SecurityPatroliController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        // ============================================================
        // CEK STATUS CHECK-IN HARI INI
        // ============================================================
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
        
        $sudahCheckIn = $attendance && $attendance->check_in != null;
        
        // Cek izin/sakit hari ini
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
            $izinStatus = $pengajuan->jenis ?? null;
        }
        
        // Cek sudah input hari ini (gunakan kolom 'tanggal' jika ada, atau 'created_at')
        $sudahInputHariIni = PatroliSecurity::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->exists();
        
        // Riwayat hari ini
        $riwayatHariIni = PatroliSecurity::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('security.patroli', compact(
            'isIzinHariIni',
            'izinStatus',
            'sudahInputHariIni',
            'riwayatHariIni',
            'sudahCheckIn' // Kirim variable ke view
        ));
    }
    
    public function store(Request $request)
    {
        try {
            $userId = Auth::id();
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            $now = Carbon::now('Asia/Jakarta');
            
            \Log::info('=== SECURITY PATROLI STORE ===');
            \Log::info('User ID: ' . $userId);
            
            // ============================================================
            // VALIDASI 1: Cek apakah user sudah check-in hari ini
            // ============================================================
            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $today)
                ->first();
            
            $sudahCheckIn = $attendance && $attendance->check_in != null;
            
            if (!$sudahCheckIn) {
                \Log::warning('User belum check-in, ID: ' . $userId);
                return redirect()->route('security.patroli')
                    ->with('error', ' Anda harus CHECK-IN terlebih dahulu sebelum dapat menginput laporan patroli!');
            }
            
            // ============================================================
            // VALIDASI 2: Cek izin/sakit
            // ============================================================
            $isIzinHariIni = Pengajuan::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->exists();
            
            if ($isIzinHariIni) {
                \Log::warning('User sedang izin/sakit, ID: ' . $userId);
                return redirect()->route('security.patroli')
                    ->with('error', 'Anda sedang IZIN/SAKIT hari ini. Tidak dapat menginput patroli.');
            }
            
            // ============================================================
            // VALIDASI 3: Cek sudah input hari ini
            // ============================================================
            $sudahInputHariIni = PatroliSecurity::where('user_id', $userId)
                ->whereDate('created_at', $today)
                ->exists();
            
            if ($sudahInputHariIni) {
                \Log::warning('User sudah input patroli hari ini, ID: ' . $userId);
                return redirect()->route('security.patroli')
                    ->with('error', 'Anda sudah menginput patroli hari ini. Hanya diperbolehkan 1 kali input per hari.');
            }
            
            // ============================================================
            // VALIDASI 4: Validasi form
            // ============================================================
            $request->validate([
                'lokasi' => 'required|array|min:1',
                'lokasi.*' => 'required|string|max:255',
                'keterangan' => 'required|array|min:1',
                'keterangan.*' => 'required|string|min:3',
                'foto' => 'required|array|min:1',
                'foto.*' => 'required|string',
            ], [
                'lokasi.required' => 'Minimal 1 area patroli wajib diisi',
                'lokasi.*.required' => 'Nama area tidak boleh kosong',
                'keterangan.required' => 'Keterangan wajib diisi',
                'keterangan.*.required' => 'Keterangan tidak boleh kosong',
                'keterangan.*.min' => 'Keterangan minimal 3 karakter',
                'foto.required' => 'Foto bukti wajib diambil',
                'foto.*.required' => 'Foto bukti wajib diambil untuk setiap area',
            ]);
            
            $savedCount = 0;
            $errors = [];
            
            foreach ($request->lokasi as $index => $lokasi) {
                \Log::info('Processing area ' . ($index + 1) . ': ' . $lokasi);
                
                $keterangan = $request->keterangan[$index] ?? '';
                $fotoBase64 = $request->foto[$index] ?? '';
                
                if (empty($fotoBase64)) {
                    $errors[] = "Foto untuk area '{$lokasi}' kosong";
                    \Log::warning('Empty foto for area: ' . $lokasi);
                    continue;
                }
                
                // Konversi base64 ke gambar
                $imageData = $this->convertBase64ToImage($fotoBase64);
                
                if (!$imageData) {
                    $errors[] = "Foto untuk area '{$lokasi}' tidak valid";
                    \Log::warning('Invalid foto for area: ' . $lokasi);
                    continue;
                }
                
                // Simpan file
                $fileName = 'patroli-security/' . $today . '/' . uniqid() . '_' . time() . '.jpg';
                $saved = Storage::disk('public')->put($fileName, $imageData);
                
                if (!$saved) {
                    $errors[] = "Gagal menyimpan foto untuk area '{$lokasi}'";
                    \Log::error('Failed to save file: ' . $fileName);
                    continue;
                }
                
                \Log::info('File saved: ' . $fileName);
                
                // Simpan ke database
                try {
                    $patroli = PatroliSecurity::create([
                        'user_id' => $userId,
                        'nama_area' => $lokasi,
                        'keterangan' => $keterangan,
                        'foto' => $fileName,
                        'waktu_patroli' => $now,
                        'tanggal' => $today, // Tambahkan jika kolom tanggal ada
                    ]);
                    
                    if ($patroli && $patroli->id) {
                        $savedCount++;
                        \Log::info('Database saved, ID: ' . $patroli->id);
                    } else {
                        $errors[] = "Gagal menyimpan data untuk area '{$lokasi}'";
                        \Log::error('Database save failed for area: ' . $lokasi);
                    }
                    
                } catch (\Exception $dbError) {
                    $errors[] = "Error database untuk area '{$lokasi}'";
                    \Log::error('DB Error: ' . $dbError->getMessage());
                    
                    // Hapus file jika gagal simpan ke DB
                    if (Storage::disk('public')->exists($fileName)) {
                        Storage::disk('public')->delete($fileName);
                    }
                }
            }
            
            \Log::info('Total saved: ' . $savedCount);
            
            if ($savedCount === 0) {
                $errorMsg = 'Gagal menyimpan data patroli.';
                if (!empty($errors)) {
                    $errorMsg .= ' ' . implode('; ', $errors);
                }
                return redirect()->back()->with('error', $errorMsg);
            }
            
            $message = "Berhasil menyimpan {$savedCount} laporan patroli.";
            if (!empty($errors)) {
                $message .= " Namun ada " . count($errors) . " data yang gagal.";
            }
            
            return redirect()->route('security.patroli')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('MAIN ERROR: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    private function convertBase64ToImage($base64String)
    {
        try {
            if (empty($base64String)) {
                return null;
            }
            
            // Jika sudah dalam format base64 murni
            if (!str_contains($base64String, 'base64,')) {
                $imageData = base64_decode($base64String);
                if ($imageData !== false && strlen($imageData) > 1000) {
                    return $imageData;
                }
                return null;
            }
            
            // Pisahkan metadata dan data
            $parts = explode('base64,', $base64String);
            if (count($parts) < 2) {
                return null;
            }
            
            $imageData = base64_decode($parts[1]);
            
            if ($imageData === false || strlen($imageData) < 1000) {
                return null;
            }
            
            return $imageData;
            
        } catch (\Exception $e) {
            \Log::error('Convert base64 error: ' . $e->getMessage());
            return null;
        }
    }
    
    public function destroy($id)
    {
        try {
            $userId = Auth::id();
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            
            // Cek check-in terlebih dahulu
            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $today)
                ->first();
            
            $sudahCheckIn = $attendance && $attendance->check_in != null;
            
            if (!$sudahCheckIn) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum melakukan check-in hari ini'
                ], 403);
            }
            
            $patroli = PatroliSecurity::where('id', $id)
                ->where('user_id', $userId)
                ->first();
            
            if (!$patroli) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            if ($patroli->foto && Storage::disk('public')->exists($patroli->foto)) {
                Storage::disk('public')->delete($patroli->foto);
            }
            
            $patroli->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Cek status check-in untuk AJAX
     */
    public function cekStatusCheckIn()
    {
        try {
            $userId = Auth::id();
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            
            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $today)
                ->first();
            
            $sudahCheckIn = $attendance && $attendance->check_in != null;
            
            return response()->json([
                'success' => true,
                'sudah_check_in' => $sudahCheckIn,
                'check_in_time' => $attendance ? $attendance->check_in : null,
                'message' => $sudahCheckIn ? 'Anda sudah melakukan check-in' : 'Anda belum melakukan check-in'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}