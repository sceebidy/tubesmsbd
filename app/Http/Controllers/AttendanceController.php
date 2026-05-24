<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\LaporanPanen;
use App\Models\KinerjaCleaning;
use App\Models\PatroliSecurity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        $today = Carbon::today('Asia/Jakarta');

        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $monthlyCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        // Data untuk user (pekerja sawit)
        $monthlyPalmWeight = 0;
        $todayPalmWeight = 0;
        
        if ($user->role == 'user') {
            $monthlyPalmWeight = LaporanPanen::where('pekerja_id', $user->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('brondolan_kg') ?? 0;
                
            $panenHariIni = LaporanPanen::where('pekerja_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();
                
            if ($panenHariIni) {
                $todayPalmWeight = $panenHariIni->brondolan_kg;
            }
        }

        $serverTime = now('Asia/Jakarta');

        return view('attendance.index', compact(
            'attendanceToday',
            'monthlyCount',
            'monthlyPalmWeight',
            'todayPalmWeight',
            'serverTime'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN (STORE)
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');

        // Validasi form
        $request->validate([
            'photo' => 'required|string',
            'checkin_latitude' => 'required|numeric',
            'checkin_longitude' => 'required|numeric',
            'checkin_address' => 'nullable|string',
            'note' => 'nullable|string|max:500',
        ]);

        // Cek sudah absen?
        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah check in hari ini');
        }

        // Validasi foto
        if (!str_contains($request->photo, 'base64')) {
            return back()->with('error', 'Foto check in tidak valid');
        }

        // ============================================================
        // VALIDASI KETERLAMBATAN - JAM 07:30
        // ============================================================
        $checkInTime = now('Asia/Jakarta');
        
        // Batas waktu check in (07:30)
        $batasWaktu = Carbon::createFromTime(7, 30, 0, 'Asia/Jakarta');
        
        // Cek keterlambatan
       // Cek keterlambatan
if ($checkInTime->gt($batasWaktu)) {
    $status = 'terlambat';
} else {
    $status = 'hadir';  // <-- GANTI 'tepat waktu' menjadi 'hadir'
}

        // Simpan foto
        $image = $request->photo;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'checkin_' . time() . '_' . rand(100, 999) . '.jpg';
        
        Storage::disk('public')->put(
            'attendance_photos/' . $imageName,
            base64_decode($image)
        );
        $photoPath = 'attendance_photos/' . $imageName;

        // Simpan database
        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => $checkInTime,
            'status' => $status,
            'photo_path' => $photoPath,
            'checkin_latitude' => $request->checkin_latitude,
            'checkin_longitude' => $request->checkin_longitude,
            'checkin_address' => $request->checkin_address,
            'note' => $request->note,
        ]);

        // Pesan berdasarkan status
        if ($status == 'terlambat') {
            $message = '⚠️ Check In berhasil, tapi Anda TERLAMBAT! Batas check in adalah pukul 07:30.';
        } else {
            $message = '✅ Check In berhasil! Selamat bekerja.';
        }

        return back()->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');

        // Cari absensi
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum check in hari ini!');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Anda sudah check out hari ini!');
        }

        // ============================================================
        // VALIDASI KHUSUS BERDASARKAN ROLE
        // ============================================================

        // VALIDASI UNTUK PEKERJA SAWIT (USER)
        if ($user->role == 'user') {
            $hasPanen = LaporanPanen::where('pekerja_id', $user->id)
                ->whereDate('tanggal', $today)
                ->exists();
                
            if (!$hasPanen) {
                return back()->with('error', 
                    '⚠️ ANDA BELUM BISA CHECKOUT! ⚠️<br><br>' .
                    'Anda harus menginput panen terlebih dahulu sebelum checkout.<br><br>' .
                    'Silakan klik menu <strong>"Input Panen Sawit"</strong> untuk input panen hari ini.'
                );
            }
        }

        // VALIDASI UNTUK CLEANING SERVICE
        if ($user->role == 'cleaning') {
            $hasKinerja = KinerjaCleaning::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->exists();
                
            if (!$hasKinerja) {
                return back()->with('error', 
                    '⚠️ ANDA BELUM BISA CHECKOUT! ⚠️<br><br>' .
                    'Anda harus menginput kinerja cleaning terlebih dahulu sebelum checkout.<br><br>' .
                    'Silakan klik menu <strong>"Input Kinerja Cleaning"</strong> untuk input kinerja hari ini.'
                );
            }
        }

        // VALIDASI UNTUK SECURITY
        if ($user->role == 'security') {
            $hasPatroli = PatroliSecurity::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->exists();
                
            if (!$hasPatroli) {
                return back()->with('error', 
                    '⚠️ ANDA BELUM BISA CHECKOUT! ⚠️<br><br>' .
                    'Anda harus menginput laporan patroli terlebih dahulu sebelum checkout.<br><br>' .
                    'Silakan klik menu <strong>"Input Patroli"</strong> untuk input patroli hari ini.'
                );
            }
        }

        // ============================================================
        // VALIDASI UNTUK MANDOR
        // ============================================================
// VALIDASI UNTUK MANDOR
if ($user->role == 'mandor') {
    $pekerjaList = User::where('mandor_id', $user->id)
        ->where('role', 'user')
        ->get();
    
    if ($pekerjaList->count() > 0) {
        // CEK APAKAH MANDOR SUDAH VERIFIKASI LAPORAN PANEN
        $sudahVerifikasi = LaporanPanen::where('mandor_id', $user->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'diverifikasi_mandor')
            ->exists();
        
        if (!$sudahVerifikasi) {
            return back()->with('error', 
                '⚠️ ANDA BELUM BISA CHECKOUT! ⚠️<br><br>' .
                'Anda harus memverifikasi laporan panen terlebih dahulu sebelum checkout.<br><br>' .
                'Silakan klik menu <strong>"Laporan Panen"</strong> untuk verifikasi laporan panen.'
            );
        }
    }
}

        // ============================================================
        // VALIDASI FORM
        // ============================================================

        $request->validate([
            'checkout_photo' => 'required|string',
            'checkout_latitude' => 'required|numeric',
            'checkout_longitude' => 'required|numeric',
            'checkout_address' => 'nullable|string',
            'note' => 'nullable|string|max:500',
        ]);

        // Validasi foto
        if (!str_contains($request->checkout_photo, 'base64')) {
            return back()->with('error', 'Foto checkout tidak valid');
        }

        // ============================================================
        // SIMPAN FOTO CHECKOUT
        // ============================================================

        $checkoutPhoto = $request->checkout_photo;
        $checkoutPhoto = str_replace('data:image/jpeg;base64,', '', $checkoutPhoto);
        $checkoutPhoto = str_replace(' ', '+', $checkoutPhoto);
        $imageName = 'checkout_' . time() . '_' . rand(100, 999) . '.jpg';
        
        Storage::disk('public')->put(
            'checkout_photos/' . $imageName,
            base64_decode($checkoutPhoto)
        );
        $checkoutPhotoPath = 'checkout_photos/' . $imageName;

        // ============================================================
        // HITUNG TOTAL JAM KERJA & VALIDASI WAKTU CHECKOUT
        // ============================================================

        $checkInTime = Carbon::parse($attendance->check_in);
        $checkOutTime = now('Asia/Jakarta');
        $batasPulangNormal = Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');
        
        $diffInMinutes = $checkOutTime->diffInMinutes($checkInTime);
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;
        $totalHours = sprintf('%d jam %d menit', $hours, $minutes);
        
        // Cek apakah checkout terlalu cepat (sebelum jam 17:00)
        $isTooEarly = $checkOutTime->lt($batasPulangNormal);
        $warningMessage = null;
        
        if ($isTooEarly) {
            $diffToNormal = $batasPulangNormal->diffInMinutes($checkOutTime);
            $earlyHours = floor($diffToNormal / 60);
            $earlyMinutes = $diffToNormal % 60;
            
            if ($earlyHours > 0) {
                $terlaluCepat = $earlyHours . ' jam ' . $earlyMinutes . ' menit';
            } else {
                $terlaluCepat = $earlyMinutes . ' menit';
            }
            
            $warningMessage = "⚠️ PERINGATAN: Anda checkout pada pukul " . $checkOutTime->format('H:i:s') . 
                             ", lebih cepat " . $terlaluCepat . " dari waktu normal (17:00 WIB).";
        }

        // ============================================================
        // UPDATE DATA
        // ============================================================

        $updateData = [
            'check_out' => $checkOutTime,
            'checkout_photo_path' => $checkoutPhotoPath,
            'checkout_latitude' => $request->checkout_latitude,
            'checkout_longitude' => $request->checkout_longitude,
            'checkout_address' => $request->checkout_address,
            'checkout_note' => $request->note,
            'total_hours' => $totalHours,
        ];

        $attendance->update($updateData);

        // ============================================================
        // PESAN SUKSES
        // ============================================================
        
        $successMessage = '✅ Check Out berhasil! ';
        
        if ($checkOutTime->gte($batasPulangNormal)) {
            $successMessage .= 'Terima kasih telah bekerja penuh hari ini. Selamat istirahat.';
        } else {
            $successMessage .= 'Lain kali harap selesaikan pekerjaan hingga pukul 17:00 WIB.';
        }
        
        // Jika ada peringatan, kirim bersama success
        if ($warningMessage) {
            return back()->with('success', $successMessage)->with('warning', $warningMessage);
        }
        
        return back()->with('success', $successMessage);
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $user = Auth::user();

        $riwayat = Attendance::where('user_id', $user->id)
            ->latest('date')
            ->paginate(10);

        return view('attendance.history', compact('riwayat'));
    }
    
    /*
    |--------------------------------------------------------------------------
    | CEK STATUS CHECKOUT (API)
    |--------------------------------------------------------------------------
    */
    
    public function cekStatusCheckout()
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
        
        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'can_checkout' => false,
                'reason' => 'Belum check in',
                'redirect' => null
            ]);
        }
        
        if ($attendance->check_out) {
            return response()->json([
                'can_checkout' => false,
                'reason' => 'Sudah checkout',
                'redirect' => null
            ]);
        }
        
        $canCheckout = true;
        $reason = null;
        $redirectRoute = null;
        
        switch ($user->role) {
            case 'user':
                $hasPanen = LaporanPanen::where('pekerja_id', $user->id)
                    ->whereDate('tanggal', $today)
                    ->exists();
                if (!$hasPanen) {
                    $canCheckout = false;
                    $reason = 'Belum input panen';
                    $redirectRoute = route('user.panen');
                }
                break;
                
            case 'cleaning':
                $hasKinerja = KinerjaCleaning::where('user_id', $user->id)
                    ->whereDate('tanggal', $today)
                    ->exists();
                if (!$hasKinerja) {
                    $canCheckout = false;
                    $reason = 'Belum input kinerja cleaning';
                    $redirectRoute = route('cleaning.kinerja');
                }
                break;
                
            case 'security':
                $hasPatroli = PatroliSecurity::where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->exists();
                if (!$hasPatroli) {
                    $canCheckout = false;
                    $reason = 'Belum input patroli';
                    $redirectRoute = route('security.patroli');
                }
                break;
                
           case 'mandor':
    $pekerjaList = User::where('mandor_id', $user->id)
        ->where('role', 'user')
        ->get();
    
    if ($pekerjaList->count() > 0) {
        // CEK APAKAH MANDOR SUDAH VERIFIKASI LAPORAN PANEN
        $sudahVerifikasi = LaporanPanen::where('mandor_id', $user->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'diverifikasi_mandor')
            ->exists();
        
        if (!$sudahVerifikasi) {
            $canCheckout = false;
            $reason = 'Belum verifikasi laporan panen';
            $redirectRoute = route('mandor.panen');
        }
    }
    break;
        }
        
        return response()->json([
            'can_checkout' => $canCheckout,
            'reason' => $reason,
            'redirect' => $redirectRoute
        ]);
    }
}