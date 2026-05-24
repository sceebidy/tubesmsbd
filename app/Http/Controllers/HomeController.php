<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\CatatanPanen;
use App\Models\LaporanPanen;
use App\Models\KinerjaCleaning;
use App\Models\PatroliSecurity;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\SheetAbsenExport;
use App\Exports\RekapSemuaExport;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            'security' => redirect()->route('security.dashboard'),
            'cleaning' => redirect()->route('cleaning.dashboard'),
            'kantoran' => redirect()->route('kantoran.dashboard'),
            'mandor' => redirect()->route('mandor.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }

    public function adminDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();

        $totalPegawai = User::whereNotIn('role', ['admin', 'manager'])->count();
        
        $hadirHariIni = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->count();
        
        $totalTerlambat = Attendance::whereDate('date', $today->toDateString())
            ->where('status', 'terlambat')
            ->count();

        $pegawaiIdsWithAttendance = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->pluck('user_id')
            ->toArray();

        $totalAlpha = User::whereNotIn('role', ['admin', 'manager'])
            ->whereNotIn('id', $pegawaiIdsWithAttendance)
            ->count();

        $produksiHariIni = CatatanPanen::whereDate('tanggal', $today->toDateString())
            ->sum('berat_kg') ?? 0;

        $totalHadirDanTerlambat = $hadirHariIni;
        $rateKehadiran = $totalPegawai > 0 ? round(($totalHadirDanTerlambat / $totalPegawai) * 100) : 0;

        $recentActivities = Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->orderBy('check_in', 'desc')
            ->limit(5)
            ->get();

        $roles = [
            'user' => 'Kebun & Panen',
            'security' => 'Security',
            'cleaning' => 'Cleaning',
            'kantoran' => 'Administrasi',
            'mandor' => 'Mandor',
        ];
        $departments = [];

        foreach ($roles as $role => $name) {
            $total = User::where('role', $role)->count();
            $hadir = Attendance::whereDate('date', $today->toDateString())
                ->whereNotNull('check_in')
                ->whereHas('user', fn($q) => $q->where('role', $role))
                ->count();
            $departments[$role] = [
                'name' => $name,
                'total' => $total,
                'hadir' => $hadir,
                'percentage' => $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        }

        return view('admin.dashboard', compact(
            'totalPegawai',
            'hadirHariIni',
            'produksiHariIni',
            'rateKehadiran',
            'recentActivities',
            'departments',
            'totalTerlambat',
            'totalAlpha'
        ));
    }

public function mandorDashboard()
{
    $today = now('Asia/Jakarta')->toDateString();
    $userId = Auth::id();

    $absenHariIni = Attendance::where('user_id', $userId)
        ->whereDate('date', $today)
        ->first();

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

    // ============================================================
    // HITUNG KEHADIRAN BULAN INI (HANYA HADIR + TERLAMBAT)
    // TIDAK TERMASUK IZIN/SAKIT
    // ============================================================
    $monthlyCount = Attendance::where('user_id', $userId)
        ->whereMonth('date', now('Asia/Jakarta')->month)
        ->whereYear('date', now('Asia/Jakarta')->year)
        ->whereIn('status', ['hadir', 'tepat waktu', 'terlambat'])
        ->count();

    $pekerjaList = User::where('mandor_id', $userId)
        ->where('role', 'user')
        ->orderBy('name')
        ->get();

    $totalPekerja = $pekerjaList->count();
    $pekerjaHadir = 0;
    $pekerjaTepatWaktu = 0;
    $pekerjaTerlambat = 0;
    
    foreach ($pekerjaList as $pekerja) {
        $absenPekerja = Attendance::where('user_id', $pekerja->id)
            ->whereDate('date', $today)
            ->first();
        
        if ($absenPekerja && $absenPekerja->check_in) {
            $pekerjaHadir++;
            if ($absenPekerja->status == 'tepat waktu') {
                $pekerjaTepatWaktu++;
            } else {
                $pekerjaTerlambat++;
            }
        }
    }

    $totalPanen = LaporanPanen::whereIn('pekerja_id', $pekerjaList->pluck('id'))
        ->whereMonth('tanggal', now('Asia/Jakarta')->month)
        ->whereYear('tanggal', now('Asia/Jakarta')->year)
        ->sum('brondolan_kg');

    $isCheckoutTooEarly = false;
    $checkoutEarlyMessage = '';
    
    if ($absenHariIni && $absenHariIni->check_out) {
        $checkoutTime = Carbon::parse($absenHariIni->check_out);
        $batasNormal = Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');
        if ($checkoutTime->lt($batasNormal)) {
            $isCheckoutTooEarly = true;
            $diff = $batasNormal->diffInMinutes($checkoutTime);
            $hours = floor($diff / 60);
            $minutes = $diff % 60;
            if ($hours > 0) {
                $checkoutEarlyMessage = $hours . ' jam ' . $minutes . ' menit lebih cepat';
            } else {
                $checkoutEarlyMessage = $minutes . ' menit lebih cepat';
            }
        }
    }

    return view('mandor.dashboard', compact(
        'absenHariIni',
        'monthlyCount',
        'pekerjaList',
        'totalPekerja',
        'pekerjaHadir',
        'pekerjaTepatWaktu',
        'pekerjaTerlambat',
        'totalPanen',
        'isCheckoutTooEarly',
        'checkoutEarlyMessage',
        'isIzinHariIni',
        'izinStatus'
    ));
}

    public function userDashboard()
{
    $user = Auth::user();
    $today = Carbon::today('Asia/Jakarta');

    // Ambil absensi hari ini
    $absenHariIni = Attendance::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->first();

    // CEK IZIN/SAKIT HARI INI
    $isIzinHariIni = Pengajuan::where('user_id', $user->id)
        ->where('status', 'disetujui')
        ->whereDate('tanggal_mulai', '<=', $today)
        ->whereDate('tanggal_selesai', '>=', $today)
        ->exists();
    
    $izinStatus = null;
    if ($isIzinHariIni) {
        $pengajuan = Pengajuan::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->first();
        $izinStatus = $pengajuan->jenis;
    }

    // Hitung total kehadiran bulan ini
    $monthlyCount = Attendance::where('user_id', $user->id)
        ->whereMonth('date', now('Asia/Jakarta')->month)
        ->whereYear('date', now('Asia/Jakarta')->year)
        ->count();

    // ============================================================
    // AMBIL DATA PANEN
    // ============================================================
    
    // AMBIL LAPORAN PANEN HARI INI (INI PENTING!)
    $laporanHariIni = LaporanPanen::where('pekerja_id', $user->id)
        ->whereDate('tanggal', $today)
        ->first();
    
    // Total brondolan bulan ini
    $totalBrondolanBulanIni = LaporanPanen::where('pekerja_id', $user->id)
        ->whereMonth('tanggal', $today->month)
        ->whereYear('tanggal', $today->year)
        ->sum('brondolan_kg') ?? 0;
    
    // Total janjangan bulan ini
    $totalJanjanganBulanIni = LaporanPanen::where('pekerja_id', $user->id)
        ->whereMonth('tanggal', $today->month)
        ->whereYear('tanggal', $today->year)
        ->sum('janjang') ?? 0;
    
    // Brondolan hari ini (dari $laporanHariIni)
    $brondolanHariIni = $laporanHariIni ? $laporanHariIni->brondolan_kg : 0;
    
    // Janjangan hari ini (dari $laporanHariIni)
    $janjanganHariIni = $laporanHariIni ? $laporanHariIni->janjang : 0;
    
    // Rata-rata per hari (opsional)
    $averageDailyPalmWeight = 0;
    if ($monthlyCount > 0 && $totalBrondolanBulanIni > 0) {
        $averageDailyPalmWeight = $totalBrondolanBulanIni / $monthlyCount;
    }

    return view('user.dashboard', [
        'absenHariIni' => $absenHariIni,
        'monthlyCount' => $monthlyCount,
        'isIzinHariIni' => $isIzinHariIni,
        'izinStatus' => $izinStatus,
        'laporanHariIni' => $laporanHariIni,           // TAMBAHKAN INI
        'totalBrondolanBulanIni' => $totalBrondolanBulanIni,  // TAMBAHKAN INI
        'totalJanjanganBulanIni' => $totalJanjanganBulanIni,   // TAMBAHKAN INI
        'brondolanHariIni' => $brondolanHariIni,              // TAMBAHKAN INI
        'janjanganHariIni' => $janjanganHariIni,              // TAMBAHKAN INI
        'averageDailyPalmWeight' => $averageDailyPalmWeight
    ]);
}

    public function managerDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today->toDateString())
            ->first();

        $isIzinHariIni = Pengajuan::where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->exists();
        
        $izinStatus = null;
        if ($isIzinHariIni) {
            $pengajuan = Pengajuan::where('user_id', Auth::id())
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->first();
            $izinStatus = $pengajuan->jenis;
        }

        $totalTim = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor'])->count();
        $hadirHariIni = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->count();

        $produksiHariIni = CatatanPanen::whereDate('tanggal', $today->toDateString())
            ->sum('berat_kg') ?? 0;

        $totalTerlambat = Attendance::whereDate('date', $today->toDateString())
            ->where('status', 'terlambat')
            ->count();

        $pegawaiIdsWithAttendance = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->pluck('user_id')
            ->toArray();

        $totalAlpha = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor'])
            ->whereNotIn('id', $pegawaiIdsWithAttendance)
            ->count();

        $produktivitasData = CatatanPanen::select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('COALESCE(SUM(berat_kg), 0) as total_produksi')
            )
            ->where('tanggal', '>=', Carbon::now('Asia/Jakarta')->subDays(30))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(function($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'total_produksi' => (float) $item->total_produksi
                ];
            });

        $topPerformers = CatatanPanen::select(
                'users.id',
                'users.name',
                'users.role',
                DB::raw('COALESCE(SUM(catatan_panen.berat_kg), 0) as total_produksi'),
                DB::raw('MAX(attendances.check_in) as check_in_time')
            )
            ->join('users', 'catatan_panen.id_pegawai', '=', 'users.id')
            ->leftJoin('attendances', function($join) use ($today) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereDate('attendances.date', $today->toDateString());
            })
            ->whereDate('catatan_panen.tanggal', $today->toDateString())
            ->where('users.role', 'user')
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderBy('total_produksi', 'desc')
            ->limit(5)
            ->get();

        if ($topPerformers->isEmpty() || $topPerformers->sum('total_produksi') == 0) {
            $topPerformers = Attendance::select(
                    'users.id',
                    'users.name',
                    'users.role',
                    DB::raw('0 as total_produksi'),
                    'attendances.check_in as check_in_time'
                )
                ->join('users', 'attendances.user_id', '=', 'users.id')
                ->whereDate('attendances.date', $today->toDateString())
                ->whereNotNull('attendances.check_in')
                ->where('users.role', 'user')
                ->orderBy('attendances.check_in', 'asc')
                ->limit(5)
                ->get();
        }

        $avgProduksi = $produktivitasData->avg('total_produksi') ?? 0;
        $totalProduksiBulanIni = CatatanPanen::whereMonth('tanggal', now('Asia/Jakarta')->month)
            ->whereYear('tanggal', now('Asia/Jakarta')->year)
            ->sum('berat_kg') ?? 0;
        $peakProduksi = $produktivitasData->max('total_produksi') ?? 0;

        $trend = 'Stabil';
        if ($produktivitasData->count() >= 2) {
            $latestData = $produktivitasData->last();
            $previousData = $produktivitasData->slice(-2, 1)->first();
            
            if ($latestData && $previousData && $previousData['total_produksi'] > 0) {
                $latest = $latestData['total_produksi'];
                $previous = $previousData['total_produksi'];
                $change = (($latest - $previous) / $previous) * 100;
                
                if ($change > 10) $trend = 'Naik';
                else if ($change < -10) $trend = 'Turun';
            }
        }

        $recentActivities = Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->whereHas('user', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']))
            ->orderBy('check_in', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $produksi = CatatanPanen::where('id_pegawai', $item->user_id)
                    ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
                    ->sum('berat_kg');
                
                $item->produksi_harian = $produksi;
                return $item;
            });

        return view('manager.dashboard', compact(
            'absenHariIni',
            'totalTim',
            'hadirHariIni',
            'produksiHariIni',
            'totalTerlambat',
            'totalAlpha',
            'produktivitasData',
            'topPerformers',
            'avgProduksi',
            'totalProduksiBulanIni',
            'peakProduksi',
            'trend',
            'recentActivities',
            'isIzinHariIni',
            'izinStatus'
        ));
    }

    public function securityDashboard()
    {
        $today = now('Asia/Jakarta')->toDateString();

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        $isIzinHariIni = Pengajuan::where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->exists();
        
        $izinStatus = null;
        if ($isIzinHariIni) {
            $pengajuan = Pengajuan::where('user_id', Auth::id())
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->first();
            $izinStatus = $pengajuan->jenis;
        }

        $patroliHariIni = PatroliSecurity::where('user_id', Auth::id())
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $totalPatroliHariIni = $patroliHariIni->count();

        return view('security.dashboard', compact(
            'absenHariIni',
            'patroliHariIni',
            'totalPatroliHariIni',
            'isIzinHariIni',
            'izinStatus'
        ));
    }

  public function cleaningDashboard()
{
    $today = now('Asia/Jakarta')->toDateString();
    $userId = auth()->id();

    $absenHariIni = Attendance::where('user_id', $userId)
        ->whereDate('date', $today)
        ->first();

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

    $jumlahAreaHariIni = KinerjaCleaning::where('user_id', $userId)
        ->whereDate('tanggal', $today)
        ->count();

    return view('cleaning.dashboard', compact(
        'absenHariIni',
        'jumlahAreaHariIni',
        'isIzinHariIni',
        'izinStatus'
    ));
}

  public function kantoranDashboard()
{
    $today = now('Asia/Jakarta')->startOfDay();
    $userId = Auth::id();
    
    $absenHariIni = Attendance::where('user_id', $userId)
        ->whereDate('date', $today->toDateString())
        ->first();

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
    
    // Hitung kehadiran bulan ini
    $monthlyCount = Attendance::where('user_id', $userId)
        ->whereMonth('date', $today->month)
        ->whereYear('date', $today->year)
        ->count();

    return view('kantoran.dashboard', compact('absenHariIni', 'isIzinHariIni', 'izinStatus', 'monthlyCount'));
}
    public function userCheckout(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return redirect()->back()->with('error', 'Anda belum check-in hari ini!');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah checkout hari ini!');
        }
        
        switch ($user->role) {
            case 'user':
                $hasPanen = CatatanPanen::where('id_pegawai', $user->id)
                    ->whereDate('tanggal', $today)
                    ->exists();
                
                if (!$hasPanen) {
                    return redirect()->back()->with('error', 
                        '⚠️ Anda belum menginput panen hari ini! Silakan input panen terlebih dahulu melalui menu "Input Panen Sawit" sebelum checkout.'
                    );
                }
                break;

            case 'cleaning':
                $hasKinerja = KinerjaCleaning::where('user_id', $user->id)
                    ->whereDate('tanggal', $today)
                    ->exists();
                
                if (!$hasKinerja) {
                    return redirect()->back()->with('error', 
                        '⚠️ Anda belum menginput kinerja cleaning hari ini! Silakan input kinerja terlebih dahulu melalui menu "Input Kinerja Cleaning" sebelum checkout.'
                    );
                }
                break;

            case 'security':
                $hasPatroli = PatroliSecurity::where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->exists();
                
                if (!$hasPatroli) {
                    return redirect()->back()->with('error', 
                        '⚠️ Anda belum menginput laporan patroli hari ini! Silakan input patroli terlebih dahulu melalui menu "Input Patroli" sebelum checkout.'
                    );
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
            return redirect()->back()->with('error', 
                '⚠️ ANDA BELUM BISA CHECKOUT! ⚠️<br><br>' .
                'Anda harus memverifikasi laporan panen terlebih dahulu sebelum checkout.<br><br>' .
                'Silakan klik menu <strong>"Laporan Panen"</strong> untuk verifikasi laporan panen.'
            );
        }
    }
    break;

            case 'kantoran':
                break;
        }
        
        $checkOutTime = Carbon::now('Asia/Jakarta');
        
        $checkInTime = Carbon::parse($attendance->check_in);
        $diffInMinutes = $checkOutTime->diffInMinutes($checkInTime);
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;
        $totalHours = sprintf('%d jam %d menit', $hours, $minutes);
        
        $attendance->update([
            'check_out' => $checkOutTime->toTimeString(),
            'total_hours' => $totalHours
        ]);
        
        $successMessage = '✅ Checkout berhasil! ';
        switch ($user->role) {
            case 'user':
                $successMessage .= 'Terima kasih telah input panen hari ini.';
                break;
            case 'cleaning':
                $successMessage .= 'Terima kasih telah mengisi kinerja cleaning.';
                break;
            case 'security':
                $successMessage .= 'Terima kasih telah melaporkan patroli.';
                break;
            case 'mandor':
                $successMessage .= 'Semua pekerja sudah input panen. Selamat istirahat.';
                break;
            case 'kantoran':
                $successMessage .= 'Selamat beristirahat.';
                break;
        }
        
        return redirect()->back()->with('success', $successMessage);
    }

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
                'reason' => 'Belum check-in',
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
                $hasPanen = CatatanPanen::where('id_pegawai', $user->id)
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
            return redirect()->back()->with('error', 
                '⚠️ ANDA BELUM BISA CHECKOUT! ⚠️<br><br>' .
                'Anda harus memverifikasi laporan panen terlebih dahulu sebelum checkout.<br><br>' .
                'Silakan klik menu <strong>"Laporan Panen"</strong> untuk verifikasi laporan panen.'
            );
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

    public function kelolaPegawai()
    {
        $pegawai = User::all();
        return view('admin.pegawai', compact('pegawai'));
    }

    public function managerPegawai()
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $pegawai = User::with('mandor')
            ->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor'])
            ->orderBy('name')
            ->get();

        $mandorList = User::where('role', 'mandor')
            ->orderBy('name')
            ->get();

        return view('manager.pegawai', compact('pegawai', 'mandorList'));
    }

    public function managerTambahPegawai(Request $request)
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:20|unique:users,no_hp',
            'role' => 'required|in:user,mandor,security,cleaning,kantoran',
            'password' => 'required|min:6',
            'mandor_id' => 'required_if:role,user|nullable|exists:users,id'
        ], [
            'mandor_id.required_if' => 'Pilih mandor untuk pekerja ini'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'mandor_id' => $request->role === 'user' ? $request->mandor_id : null
        ]);

        return redirect()->route('manager.pegawai')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function managerUpdatePegawai(Request $request, $id)
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $pegawai = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_hp' => 'required|string|max:20|unique:users,no_hp,' . $id,
            'role' => 'required|in:user,mandor,security,cleaning,kantoran',
            'mandor_id' => 'required_if:role,user|nullable|exists:users,id'
        ], [
            'mandor_id.required_if' => 'Pilih mandor untuk pekerja ini'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'mandor_id' => $request->role === 'user' ? $request->mandor_id : null
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('manager.pegawai')->with('success', 'Data pegawai berhasil diupdate!');
    }

    public function managerHapusPegawai($id)
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $pegawai = User::findOrFail($id);

        $hasAttendance = Attendance::where('user_id', $id)->exists();
        $hasPanen = CatatanPanen::where('id_pegawai', $id)->exists();
        $hasRapot = \App\Models\Rapot::where('id_user', $id)->exists();
        $hasChildren = User::where('mandor_id', $id)->exists();

        if ($hasAttendance || $hasPanen || $hasRapot || $hasChildren) {
            return redirect()->route('manager.pegawai')->with('warning', 
                'Pegawai memiliki riwayat data atau memiliki anak buah. Gunakan Hapus Paksa untuk menghapus semua data terkait.');
        }

        $pegawai->delete();

        return redirect()->route('manager.pegawai')->with('success', 'Pegawai berhasil dihapus!');
    }

    public function managerForceDeletePegawai(Request $request, $id)
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $pegawai = User::findOrFail($id);

        if (!$request->has('confirm_delete') || $request->confirm_delete !== 'YA') {
            return redirect()->route('manager.pegawai')->with('error', 
                'Konfirmasi tidak valid. Harap centang konfirmasi dan ketik YA.');
        }

        DB::beginTransaction();
        
        try {
            $pegawaiName = $pegawai->name;
            $pegawaiId = $pegawai->id;
            
            if ($pegawai->role === 'mandor') {
                User::where('mandor_id', $pegawaiId)->update(['mandor_id' => null]);
            }
            
            if (class_exists('\App\Models\Rapot')) {
                \App\Models\Rapot::where('id_user', $pegawaiId)->delete();
                \App\Models\Rapot::where('evaluator_id', $pegawaiId)->update(['evaluator_id' => null]);
            }
            
            CatatanPanen::where('id_pegawai', $pegawaiId)->delete();
            Attendance::where('user_id', $pegawaiId)->delete();
            
            if (class_exists('\App\Models\Announcement')) {
                \App\Models\Announcement::where('created_by', $pegawaiId)->update(['created_by' => null]);
            }
            
            $pegawai->delete();
            
            DB::commit();
            
            return redirect()->route('manager.pegawai')->with('success', 
                "Pegawai <strong>$pegawaiName</strong> berhasil dihapus beserta semua riwayatnya!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Force delete pegawai gagal: ' . $e->getMessage());
            
            return redirect()->route('manager.pegawai')->with('error', 
                'Terjadi kesalahan saat menghapus pegawai: ' . $e->getMessage());
        }
    }

    public function managerLog(Request $request)
    {
        if (Auth::user()->role !== 'manager') {
            return redirect('/');
        }

        $today = now('Asia/Jakarta')->startOfDay();

        $dateFilter = $request->input('date_filter', 'today');

        if ($dateFilter === 'all') {
            $selectedDate = null;
        } elseif ($dateFilter === 'custom') {
            $selectedDate = $request->date
                ? Carbon::parse($request->date, 'Asia/Jakarta')->startOfDay()
                : $today;
        } else {
            $dateFilter = 'today';
            $selectedDate = $today;
        }

        $query = Attendance::with('user');

        if ($dateFilter !== 'all') {
            $query->whereDate('date', $selectedDate->toDateString());
        }

        if ($request->filled('role')) {
            $query->whereHas('user', fn($q) => $q->where('role', $request->role));
        } else {
            $query->whereHas('user', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('no_hp', 'like', "%{$request->search}%");
            });
        }

        $attendances = $query
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(10)
            ->appends($request->except('page'));

        $totalPegawai = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor'])
            ->when($request->filled('role'), fn($q) => $q->where('role', $request->role))
            ->count();

        $applyRoleFilter = function ($q) use ($request) {
            if ($request->filled('role')) {
                $q->whereHas('user', fn($q2) => $q2->where('role', $request->role));
            } else {
                $q->whereHas('user', fn($q2) => $q2->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']));
            }
        };

        if ($dateFilter === 'all') {
            $totalHadir = Attendance::where('status', 'tepat waktu')
                ->tap($applyRoleFilter)
                ->count();

            $totalTerlambat = Attendance::where('status', 'terlambat')
                ->tap($applyRoleFilter)
                ->count();

            $pernahHadirIds = Attendance::whereNotNull('check_in')
                ->tap($applyRoleFilter)
                ->pluck('user_id')
                ->unique()
                ->toArray();

            $totalAlpha = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor'])
                ->when($request->filled('role'), fn($q) => $q->where('role', $request->role))
                ->whereNotIn('id', $pernahHadirIds)
                ->count();
        } else {
            $date = $selectedDate->toDateString();

            $totalHadir = Attendance::whereDate('date', $date)
                ->where('status', 'tepat waktu')
                ->tap($applyRoleFilter)
                ->distinct('user_id')
                ->count('user_id');

            $totalTerlambat = Attendance::whereDate('date', $date)
                ->where('status', 'terlambat')
                ->tap($applyRoleFilter)
                ->count();

            $hadirIds = Attendance::whereDate('date', $date)
                ->whereNotNull('check_in')
                ->tap($applyRoleFilter)
                ->pluck('user_id')
                ->toArray();

            $totalAlpha = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor'])
                ->when($request->filled('role'), fn($q) => $q->where('role', $request->role))
                ->whereNotIn('id', $hadirIds)
                ->count();
        }

        if ($dateFilter === 'all') {
            $displayDate = 'Semua Tanggal';
        } elseif ($dateFilter === 'custom' && $selectedDate) {
            $displayDate = $selectedDate->toDateString();
        } else {
            $displayDate = $today->toDateString();
        }

        return view('manager.log', compact(
            'attendances',
            'totalPegawai',
            'totalHadir',
            'totalTerlambat',
            'totalAlpha',
            'selectedDate',
            'displayDate',
            'dateFilter'
        ));
    }

public function laporanAdmin(Request $request)
{
    $today = now('Asia/Jakarta')->startOfDay();

    $startDate = $request->start_date
        ? Carbon::parse($request->start_date)->startOfDay()
        : $today->copy()->startOfMonth();

    $endDate = $request->end_date
        ? Carbon::parse($request->end_date)->endOfDay()
        : $today->copy()->endOfMonth();

    $role = $request->input('role');
    $dataType = $request->input('data_type', 'today');

    // ============================================================
    // HITUNG TOTAL PEGAWAI PER ROLE
    // ============================================================
    $allRoles = ['user', 'mandor', 'kantoran', 'cleaning', 'security'];

    $totalPegawaiPerRole = [];

    foreach ($allRoles as $r) {
        $totalPegawaiPerRole[$r] = User::where('role', $r)->count();
    }

    $totalSemuaPegawai = array_sum($totalPegawaiPerRole);

    // ============================================================
    // TOTAL PEGAWAI
    // ============================================================
    $userQuery = User::whereIn('role', $allRoles);

    if ($role) {
        $userQuery->where('role', $role);
    }

    $totalPegawai = $userQuery->count();
    $totalPegawaiRoleCount = $totalPegawai;

    // ============================================================
    // QUERY ATTENDANCE
    // ============================================================
    $attendanceQuery = Attendance::with('user');

    if ($dataType == 'today') {

        $attendanceQuery->whereDate('date', $today->toDateString());

    } else {

        $attendanceQuery->whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ]);
    }

    if ($role) {

        $attendanceQuery->whereHas('user', function ($q) use ($role) {
            $q->where('role', $role);
        });

    } else {

        $attendanceQuery->whereHas('user', function ($q) use ($allRoles) {
            $q->whereIn('role', $allRoles);
        });
    }

    $detailedAttendances = $attendanceQuery
        ->orderBy('date', 'desc')
        ->orderBy('check_in', 'desc')
        ->paginate(20)
        ->appends($request->except('page'));

    // ============================================================
    // DATA IZIN / SAKIT & VERIFIKASI MANDOR
    // ============================================================
    $totalVerifikasiMandor = 0;
    $totalBeratMandor = 0;
    $totalTandanMandor = 0;

    foreach ($detailedAttendances as $attendance) {

        // ========================================================
        // CEK IZIN / SAKIT
        // ========================================================
        $pengajuan = Pengajuan::where('user_id', $attendance->user_id)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $attendance->date)
            ->whereDate('tanggal_selesai', '>=', $attendance->date)
            ->first();

        $attendance->is_izin_sakit = $pengajuan ? true : false;

        $attendance->jenis_izin_sakit = $pengajuan
            ? $pengajuan->jenis
            : null;

        $attendance->status_display = $pengajuan
            ? $pengajuan->jenis
            : ($attendance->status == 'tepat waktu'
                ? 'hadir'
                : $attendance->status);

        // ========================================================
        // DATA MANDOR
        // ========================================================
        if ($attendance->user?->role == 'mandor') {

            $verifikasiPanen = LaporanPanen::where('mandor_id', $attendance->user_id)
                ->whereDate('tanggal', $attendance->date)
                ->with('pekerja')
                ->get();

            $attendance->verifikasi_panen = $verifikasiPanen;

            // Total laporan diverifikasi
            $attendance->total_verifikasi = $verifikasiPanen->count();

            // Total berat panen anggota
            $attendance->total_berat = $verifikasiPanen->sum('total_berat_kg');

            // TOTAL JANJANG / TANDAN DARI SEMUA ANGGOTA
            $attendance->total_tandan = $verifikasiPanen->sum('janjang');
        }
    }

    // ============================================================
    // TOTAL CARD MANDOR
    // ============================================================
    if ($role == 'mandor') {

        $verifikasiQuery = LaporanPanen::whereHas('mandor', function ($q) {
            $q->where('role', 'mandor');
        });

        if ($dataType == 'today') {

            $verifikasiQuery->whereDate(
                'tanggal',
                $today->toDateString()
            );

        } else {

            $verifikasiQuery->whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ]);
        }

        // Total laporan diverifikasi
        $totalVerifikasiMandor = $verifikasiQuery->count();

        // Total berat panen semua anggota
        $totalBeratMandor = $verifikasiQuery->sum('total_berat_kg') ?? 0;

        // TOTAL JANJANG / TANDAN SEMUA ANGGOTA
        $totalTandanMandor = $verifikasiQuery->sum('janjang') ?? 0;
    }

    // ============================================================
    // TOTAL HADIR
    // ============================================================
    $hadirQuery = Attendance::whereIn('status', [
        'hadir',
        'tepat waktu',
        'terlambat'
    ]);

    if ($dataType == 'today') {

        $hadirQuery->whereDate('date', $today->toDateString());

    } else {

        $hadirQuery->whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ]);
    }

    if ($role) {

        $hadirQuery->whereHas('user', function ($q) use ($role) {
            $q->where('role', $role);
        });

    } else {

        $hadirQuery->whereHas('user', function ($q) use ($allRoles) {
            $q->whereIn('role', $allRoles);
        });
    }

    $totalHadir = $hadirQuery
        ->distinct('user_id')
        ->count('user_id');

    // ============================================================
    // DATA USER / PEKERJA SAWIT
    // ============================================================
    $totalBrondolan = 0;
    $totalJanjang = 0;
    $rataRataBrondolan = 0;
    $dailyBrondolan = collect();

    if (!$role || $role == 'user') {

        $panenQuery = LaporanPanen::whereHas('pekerja', function ($q) {
            $q->where('role', 'user');
        });

        if ($dataType == 'today') {

            $panenQuery->whereDate(
                'tanggal',
                $today->toDateString()
            );

        } else {

            $panenQuery->whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ]);
        }

        $totalBrondolan = $panenQuery->sum('brondolan_kg') ?? 0;

        $totalJanjang = $panenQuery->sum('janjang') ?? 0;

        $jumlahPekerja = User::where('role', 'user')->count();

        $rataRataBrondolan = $jumlahPekerja > 0
            ? $totalBrondolan / $jumlahPekerja
            : 0;

        // ========================================================
        // CHART 7 HARI
        // ========================================================
        $chartEndDate = $today->copy();

        $chartStartDate = $chartEndDate->copy()->subDays(6);

        $dailyBrondolan = LaporanPanen::select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(brondolan_kg) as total_brondolan')
            )
            ->whereBetween('tanggal', [
                $chartStartDate->toDateString(),
                $chartEndDate->toDateString()
            ])
            ->whereHas('pekerja', function ($q) {
                $q->where('role', 'user');
            })
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();
    }

    // ============================================================
    // DATA SECURITY
    // ============================================================
    $totalPatroli = 0;
    $totalLokasiPatroli = 0;

    if ($role == 'security') {

        $patroliQuery = PatroliSecurity::query();

        if ($dataType == 'today') {

            $patroliQuery->whereDate(
                'created_at',
                $today->toDateString()
            );

        } else {

            $patroliQuery->whereBetween('created_at', [
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString()
            ]);
        }

        $totalPatroli = $patroliQuery->count();

        $totalLokasiPatroli = $patroliQuery
            ->distinct('nama_area')
            ->count('nama_area');
    }

    // ============================================================
    // DATA CLEANING
    // ============================================================
    $totalKinerja = 0;
    $totalAreaKinerja = 0;

    if ($role == 'cleaning') {

        $kinerjaQuery = KinerjaCleaning::query();

        if ($dataType == 'today') {

            $kinerjaQuery->whereDate(
                'tanggal',
                $today->toDateString()
            );

        } else {

            $kinerjaQuery->whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ]);
        }

        $totalKinerja = $kinerjaQuery->count();

        $totalAreaKinerja = $kinerjaQuery
            ->distinct('area')
            ->count('area');
    }

    // ============================================================
    // CHART KEHADIRAN
    // ============================================================
    $chartEndDate = $today->copy();

    $chartStartDate = $chartEndDate->copy()->subDays(6);

    $dailyAttendance = Attendance::select(
            DB::raw('DATE(date) as date'),
            DB::raw('COUNT(DISTINCT user_id) as total')
        )
        ->whereBetween('date', [
            $chartStartDate->toDateString(),
            $chartEndDate->toDateString()
        ])
        ->whereIn('status', [
            'hadir',
            'tepat waktu',
            'terlambat'
        ])
        ->when($role,
            function ($q) use ($role) {
                $q->whereHas('user', function ($q2) use ($role) {
                    $q2->where('role', $role);
                });
            },
            function ($q) use ($allRoles) {
                $q->whereHas('user', function ($q2) use ($allRoles) {
                    $q2->whereIn('role', $allRoles);
                });
            }
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // ============================================================
    // TODAY BANNER
    // ============================================================
    $todayAttendanceCount = 0;
    $todayPalmWeight = 0;

    if (!$role || $role == 'user') {

        $todayAttendanceCount = Attendance::whereDate(
                'date',
                $today->toDateString()
            )
            ->whereIn('status', [
                'hadir',
                'tepat waktu',
                'terlambat'
            ])
            ->whereHas('user', function ($q) {
                $q->where('role', 'user');
            })
            ->distinct('user_id')
            ->count('user_id');

        $todayPalmWeight = LaporanPanen::whereDate(
                'tanggal',
                $today->toDateString()
            )
            ->whereHas('pekerja', function ($q) {
                $q->where('role', 'user');
            })
            ->sum('brondolan_kg') ?? 0;
    }

    // ============================================================
    // RETURN VIEW
    // ============================================================
    return view('admin.laporan', compact(
        'startDate',
        'endDate',
        'dataType',
        'totalSemuaPegawai',
        'totalPegawaiPerRole',
        'totalPegawai',
        'totalPegawaiRoleCount',
        'totalHadir',
        'detailedAttendances',
        'totalBrondolan',
        'totalJanjang',
        'rataRataBrondolan',
        'dailyBrondolan',
        'totalPatroli',
        'totalLokasiPatroli',
        'totalKinerja',
        'totalAreaKinerja',
        'dailyAttendance',
        'todayAttendanceCount',
        'todayPalmWeight',
        'totalVerifikasiMandor',
        'totalBeratMandor',
        'totalTandanMandor'
    ));
}

    public function laporanManager(Request $request)
    {
        if (Auth::user()->role !== 'manager') {
            return redirect('/');
        }

        $today = now('Asia/Jakarta')->startOfDay();

        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : $today->copy()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : $today->copy()->endOfMonth();

        $role = $request->input('role');
        $dataType = $request->input('data_type', 'today');

        $query = Attendance::with('user')
            ->whereNotNull('check_in');

        if ($dataType == 'today') {
            $query->whereDate('date', $today->toDateString());
        } else {
            $query->whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ]);
        }

        if ($role) {
            $query->whereHas('user', function($q) use ($role) {
                $q->where('role', $role);
            });
        } else {
            $query->whereHas('user', function($q) {
                $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']);
            });
        }

        $detailedAttendances = $query->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(20)
            ->appends($request->except('page'));

        $userQuery = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']);
        if ($role) {
            $userQuery->where('role', $role);
        }
        $totalPegawai = $userQuery->count();

        $totalPalmWeight = 0;
        $averagePalmWeight = 0;
        
        if (!$role || $role == 'user') {
            $palmQuery = CatatanPanen::query();
            
            if ($dataType == 'today') {
                $palmQuery->whereDate('tanggal', $today->toDateString());
            } else {
                $palmQuery->whereBetween('tanggal', [
                    $startDate->toDateString(),
                    $endDate->toDateString()
                ]);
            }
            
            if ($role == 'user') {
                $palmQuery->whereHas('pegawai', function($q) {
                    $q->where('role', 'user');
                });
            } else {
                $palmQuery->whereHas('pegawai', function($q) {
                    $q->where('role', 'user');
                });
            }
            
            $totalPalmWeight = $palmQuery->sum('berat_kg') ?? 0;
            $countPanen = $palmQuery->distinct('id_pegawai')->count('id_pegawai');
            $averagePalmWeight = $countPanen > 0 ? round($totalPalmWeight / $countPanen, 2) : 0;
        }

        $hadirQuery = Attendance::whereNotNull('check_in');
        
        if ($dataType == 'today') {
            $hadirQuery->whereDate('date', $today->toDateString());
        } else {
            $hadirQuery->whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ]);
        }
        
        if ($role) {
            $hadirQuery->whereHas('user', function($q) use ($role) {
                $q->where('role', $role);
            });
        } else {
            $hadirQuery->whereHas('user', function($q) {
                $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']);
            });
        }
        
        $totalHadir = $hadirQuery->distinct('user_id')->count('user_id');

        $chartEndDate = now('Asia/Jakarta')->startOfDay();
        $chartStartDate = $chartEndDate->copy()->subDays(6);
        
        $dailyPalmWeight = CatatanPanen::select(
                DB::raw('DATE(tanggal) as date'),
                DB::raw('SUM(berat_kg) as total_weight')
            )
            ->whereBetween('tanggal', [
                $chartStartDate->toDateString(),
                $chartEndDate->toDateString()
            ])
            ->whereHas('pegawai', function($q) use ($role) {
                if ($role) {
                    $q->where('role', $role);
                } else {
                    $q->where('role', 'user');
                }
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyAttendance = Attendance::select(
                DB::raw('DATE(date) as date'),
                DB::raw('COUNT(DISTINCT user_id) as total')
            )
            ->whereBetween('date', [
                $chartStartDate->toDateString(),
                $chartEndDate->toDateString()
            ])
            ->whereNotNull('check_in')
            ->when($role, function($q) use ($role) {
                $q->whereHas('user', function($q2) use ($role) {
                    $q2->where('role', $role);
                });
            }, function($q) {
                $q->whereHas('user', function($q2) {
                    $q2->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']);
                });
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topPerformers = collect();
        if (!$role || $role == 'user') {
            $topPerformersQuery = CatatanPanen::with('pegawai')
                ->select(
                    'id_pegawai',
                    DB::raw('SUM(berat_kg) as total_weight'),
                    DB::raw('COUNT(*) as total_days')
                );
            
            if ($dataType == 'today') {
                $topPerformersQuery->whereDate('tanggal', $today->toDateString());
            } else {
                $topPerformersQuery->whereBetween('tanggal', [
                    $startDate->toDateString(),
                    $endDate->toDateString()
                ]);
            }
            
            $topPerformers = $topPerformersQuery
                ->when($role == 'user', function($q) {
                    $q->whereHas('pegawai', function($q2) {
                        $q2->where('role', 'user');
                    });
                }, function($q) {
                    $q->whereHas('pegawai', function($q2) {
                        $q2->where('role', 'user');
                    });
                })
                ->groupBy('id_pegawai')
                ->orderBy('total_weight', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    $item->total_hadir = $item->total_days;
                    return $item;
                });
        }

        $hasPalmAccess = !$role || $role == 'user';

        $todayAttendanceCount = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->when($role, function($q) use ($role) {
                $q->whereHas('user', function($q2) use ($role) {
                    $q2->where('role', $role);
                });
            }, function($q) {
                $q->whereHas('user', function($q2) {
                    $q2->whereIn('role', ['user', 'security', 'cleaning', 'kantoran', 'mandor']);
                });
            })
            ->distinct('user_id')
            ->count('user_id');

        $todayPalmWeight = CatatanPanen::whereDate('tanggal', $today->toDateString())
            ->when($role == 'user', function($q) {
                $q->whereHas('pegawai', function($q2) {
                    $q2->where('role', 'user');
                });
            }, function($q) {
                $q->whereHas('pegawai', function($q2) {
                    $q2->where('role', 'user');
                });
            })
            ->sum('berat_kg') ?? 0;

        return view('manager.laporan', compact(
            'startDate',
            'endDate',
            'role',
            'dataType',
            'totalPegawai',
            'totalPalmWeight',
            'averagePalmWeight',
            'totalHadir',
            'dailyPalmWeight',
            'dailyAttendance',
            'topPerformers',
            'detailedAttendances',
            'hasPalmAccess',
            'todayAttendanceCount',
            'todayPalmWeight'
        ));
    }

    public function userRiwayat()
    {
        $userId = Auth::id();
        
        $attendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->paginate(10);

        $panenHistory = CatatanPanen::where('id_pegawai', $userId)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('user.riwayat', compact('attendances', 'panenHistory'));
    }

    public function userAbsen(Request $request)
    {
        $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string|max:255',
        ]);

        $today = now('Asia/Jakarta')->startOfDay();
        $userId = Auth::id();

        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today->toDateString())
            ->first();

        if ($existingAttendance) {
            return redirect()->route('user.dashboard')->with('error', 'Anda sudah absen hari ini!');
        }

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('attendance-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $checkInTime = now('Asia/Jakarta');
        $jamMasuk = Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta');
        $status = $checkInTime->greaterThan($jamMasuk) ? 'terlambat' : 'tepat waktu';

        Attendance::create([
            'user_id' => $userId,
            'date' => $today->toDateString(),
            'check_in' => $checkInTime->toTimeString(),
            'status' => $status,
            'photos' => !empty($photoPaths) ? json_encode($photoPaths) : null,
            'description' => $request->description ?? null,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Absen berhasil!');
    }

    public function exportAllCsv()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $from = request('from');
        $to   = request('to');

        if (!$from || !$to) {
            return redirect()->back()->with('error', 'Harap pilih tanggal mulai dan tanggal akhir');
        }

        try {
            \Carbon\Carbon::parse($from);
            \Carbon\Carbon::parse($to);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Format tanggal tidak valid');
        }

        return Excel::download(
            new \App\Exports\RekapSemuaExport($from, $to),
            "rekap_semua_{$from}_{$to}.xlsx"
        );
    }

    public function exportAllCsvAllTime()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $firstAttendance = \App\Models\Attendance::min('date');
        $lastAttendance = \App\Models\Attendance::max('date');
        
        $firstPanen = \App\Models\CatatanPanen::min('tanggal');
        $lastPanen = \App\Models\CatatanPanen::max('tanggal');
        
        $from = min($firstAttendance, $firstPanen) ?: now()->subMonth()->format('Y-m-d');
        $to = max($lastAttendance, $lastPanen) ?: now()->format('Y-m-d');

        return Excel::download(
            new \App\Exports\RekapSemuaExport($from, $to),
            "rekap_semua_data.xlsx"
        );
    }

    public function exportSheetAbsen()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $from = request('from');
        $to   = request('to');

        if (!$from || !$to) {
            return redirect()->back()->with('error', 'Harap pilih tanggal mulai dan tanggal akhir');
        }

        return Excel::download(
            new \App\Exports\SheetAbsenExport($from, $to),
            "rekap_absen_per_pegawai_{$from}_{$to}.xlsx"
        );
    }

    public function userInputPanen(Request $request)
    {
        $request->validate([
            'berat_kg' => 'required|numeric|min:0.1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        CatatanPanen::create([
            'id_pegawai' => Auth::id(),
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'berat_kg' => $request->berat_kg,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Data panen berhasil disimpan!');
    }
}