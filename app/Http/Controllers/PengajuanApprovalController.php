<?php
// app/Http/Controllers/PengajuanApprovalController.php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengajuanApprovalController extends Controller
{
    /**
     * Menampilkan daftar pengajuan yang perlu di-approve
     */
    public function index()
    {
        // Ambil pengajuan dari role yang diizinkan
        $pengajuan = Pengajuan::with('user')
            ->whereHas('user', function($query) {
                $query->whereIn('role', ['user', 'cleaning', 'kantoran', 'security', 'mandor']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Statistik pengajuan
        $statistik = [
            'pending' => Pengajuan::whereHas('user', function($q) {
                $q->whereIn('role', ['user', 'cleaning', 'kantoran', 'security', 'mandor']);
            })->where('status', 'pending')->count(),
            'disetujui' => Pengajuan::whereHas('user', function($q) {
                $q->whereIn('role', ['user', 'cleaning', 'kantoran', 'security', 'mandor']);
            })->where('status', 'disetujui')->count(),
            'ditolak' => Pengajuan::whereHas('user', function($q) {
                $q->whereIn('role', ['user', 'cleaning', 'kantoran', 'security', 'mandor']);
            })->where('status', 'ditolak')->count(),
        ];
        
        return view('admin.pengajuan.approval', compact('pengajuan', 'statistik'));
    }
    
    /**
     * Menyetujui pengajuan
     */
    public function approve($id)
    {
        DB::beginTransaction();
        
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            // Cek role user yang mengajukan
            if (!in_array($pengajuan->user->role, ['user', 'cleaning', 'kantoran', 'security', 'mandor'])) {
                return back()->with('error', 'Role ini tidak memerlukan approval');
            }
            
            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan sudah diproses sebelumnya');
            }
            
            // Update status
            $pengajuan->update([
                'status' => 'disetujui',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            
            // Buat absensi otomatis
            $createdCount = $this->createAttendanceRecords($pengajuan);
            
            DB::commit();
            
            $jumlahHari = $pengajuan->jumlah_hari;
            $pesan = $pengajuan->jenis == 'izin' 
                ? "✓ Pengajuan IZIN dari {$pengajuan->user->name} ({$pengajuan->user->role}) untuk {$jumlahHari} hari telah DISETUJUI. {$createdCount} record absensi ditambahkan."
                : "✓ Pengajuan SAKIT dari {$pengajuan->user->name} ({$pengajuan->user->role}) untuk {$jumlahHari} hari telah DISETUJUI. {$createdCount} record absensi ditambahkan.";
            
            return redirect()->route('admin.pengajuan.approval.index')->with('success', $pesan);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error approving pengajuan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Membuat record absensi otomatis untuk semua tanggal dalam range pengajuan
     */
    private function createAttendanceRecords($pengajuan)
    {
        $start = Carbon::parse($pengajuan->tanggal_mulai)->startOfDay();
        $end = Carbon::parse($pengajuan->tanggal_selesai)->endOfDay();
        $currentDate = clone $start;
        $createdCount = 0;
        
        while ($currentDate <= $end) {
            // Cek apakah sudah ada absensi di tanggal tersebut
            $existing = Attendance::where('user_id', $pengajuan->user_id)
                ->whereDate('date', $currentDate)
                ->first();
            
            if (!$existing) {
                // Buat absensi baru
                Attendance::create([
                    'user_id' => $pengajuan->user_id,
                    'date' => $currentDate->toDateString(),
                    'status' => $pengajuan->jenis, // 'izin' atau 'sakit'
                    'check_in' => null,
                    'check_out' => null,
                    'note' => $pengajuan->jenis == 'izin' ? 'Izin (disetujui)' : 'Sakit (disetujui)',
                ]);
                $createdCount++;
            } else {
                // Update absensi yang sudah ada, tapi jangan overwrite jika sudah ada check_in
                if (!$existing->check_in) {
                    $existing->update([
                        'status' => $pengajuan->jenis,
                        'note' => $pengajuan->jenis == 'izin' ? 'Izin (disetujui)' : 'Sakit (disetujui)',
                    ]);
                    $createdCount++;
                }
            }
            
            $currentDate->addDay();
        }
        
        return $createdCount;
    }
    
    /**
     * Menolak pengajuan
     */
    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Pengajuan sudah diproses sebelumnya');
            }
            
            $pengajuan->update([
                'status' => 'ditolak',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'catatan_admin' => $request->keterangan,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.pengajuan.approval.index')->with('success', "✗ Pengajuan dari {$pengajuan->user->name} ditolak");
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error rejecting pengajuan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Detail pengajuan
     */
    public function detail($id)
    {
        $pengajuan = Pengajuan::with(['user', 'approver'])->findOrFail($id);
        
        // Cek role user yang mengajukan
        if (!in_array($pengajuan->user->role, ['user', 'cleaning', 'kantoran', 'security', 'mandor'])) {
            abort(404);
        }
        
        // Hitung jumlah hari
        $jumlahHari = $pengajuan->jumlah_hari;
        
        return view('admin.pengajuan.approval_detail', compact('pengajuan', 'jumlahHari'));
    }
    
    /**
     * Hapus pengajuan (tambahan)
     */
    public function destroy($id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if ($pengajuan->status !== 'pending') {
                return back()->with('error', 'Hanya pengajuan pending yang dapat dihapus');
            }
            
            $pengajuan->delete();
            
            return redirect()->route('admin.pengajuan.approval.index')->with('success', 'Pengajuan berhasil dihapus');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve massal (tambahan)
     */
    public function approveMass(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada pengajuan yang dipilih');
        }
        
        DB::beginTransaction();
        
        try {
            $approved = 0;
            
            foreach ($ids as $id) {
                $pengajuan = Pengajuan::find($id);
                if ($pengajuan && $pengajuan->status === 'pending') {
                    $pengajuan->update([
                        'status' => 'disetujui',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                    ]);
                    $this->createAttendanceRecords($pengajuan);
                    $approved++;
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.pengajuan.approval.index')->with('success', "$approved pengajuan berhasil disetujui");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}