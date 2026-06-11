@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-4 md:p-8">
    <div class="container mx-auto max-w-6xl px-2 sm:px-6">

        {{-- HEADER SECTION --}}
        <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-[#eaf4f1] rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L6 8H9L5 14H8L4 20H8L12 22L16 20H20L16 14H19L15 8H18L12 2Z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Input Panen Sawit</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Masukkan data panen sawit untuk hari ini</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs md:text-sm text-gray-500">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, j F Y') }}</p>
                        <span class="inline-block px-3 py-1 md:px-4 md:py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-xs md:text-sm font-medium mt-1">
                            PT. Sipirok Indah
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#e8f5f0] border border-[#2e7d5e]/20 flex items-center gap-3" id="successMessage">
            <svg class="w-5 h-5 text-[#2e7d5e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm md:text-base text-[#1f4a3d] flex-1">{!! session('success') !!}</p>
            <button type="button" onclick="this.closest('div').remove()" class="text-[#2c5e4e]/60 hover:text-[#2c5e4e] text-xl leading-none">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#FDECEA] border border-[#C0392B]/20 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#C0392B] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm md:text-base text-[#7B1C14] flex-1">{!! session('error') !!}</p>
            <button type="button" onclick="this.closest('div').remove()" class="text-[#C0392B]/60 hover:text-[#C0392B] text-xl leading-none">&times;</button>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- VALIDASI SEMUA PRIORITAS --}}
        {{-- ============================================================ --}}
        @php
            $today = \Carbon\Carbon::today('Asia/Jakarta');
            $userId = Auth::id();
            $user = Auth::user();
            
            $hasMandor = !is_null($user->mandor_id);
            
            $isIzinHariIni = \App\Models\Pengajuan::where('user_id', $userId)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->exists();
            
            $izinStatus = null;
            if ($isIzinHariIni) {
                $pengajuan = \App\Models\Pengajuan::where('user_id', $userId)
                    ->where('status', 'disetujui')
                    ->whereDate('tanggal_mulai', '<=', $today)
                    ->whereDate('tanggal_selesai', '>=', $today)
                    ->first();
                $izinStatus = $pengajuan->jenis;
            }
            
            $sudahCheckIn = false;
            if ($hasMandor && !$isIzinHariIni) {
                $sudahCheckIn = \App\Models\Attendance::where('user_id', $userId)
                    ->whereDate('date', $today)
                    ->whereNotNull('check_in')
                    ->exists();
            }
            
            $laporanHariIni = \App\Models\LaporanPanen::where('pekerja_id', $userId)
                ->whereDate('tanggal', $today)
                ->first();
            $sudahInputHariIni = !is_null($laporanHariIni);
            
            $mandorSudahVerifikasi = false;
            if($user->mandor_id) {
                $mandorSudahVerifikasi = \App\Models\LaporanPanen::where('mandor_id', $user->mandor_id)
                    ->whereDate('tanggal', $today)
                    ->where('status', 'diverifikasi_mandor')
                    ->exists();
            }
            
            $riwayatPanen = \App\Models\LaporanPanen::where('pekerja_id', $userId)
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
        @endphp

        {{-- PERINGATAN BELUM MEMILIKI MANDOR --}}
        @if(!$hasMandor)
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-red-800 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        BELUM MEMILIKI MANDOR!
                    </p>
                    <p class="text-sm text-red-700 mt-1">
                        Anda tidak dapat menginput panen karena belum memiliki mandor.<br>
                        Silakan hubungi administrator untuk ditugaskan ke mandor terlebih dahulu.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- PERINGATAN IZIN/SAKIT --}}
        @if($hasMandor && $isIzinHariIni)
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-blue-800 text-lg flex items-center gap-2">
                        @if($izinStatus == 'izin')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Anda sedang IZIN pada hari ini
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Anda sedang SAKIT pada hari ini
                        @endif
                    </p>
                    <p class="text-sm text-blue-700 mt-1">
                        Pengajuan Anda telah disetujui. Anda tidak perlu menginput panen pada hari ini.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- PERINGATAN BELUM CHECK IN --}}
        @if($hasMandor && !$isIzinHariIni && !$sudahCheckIn)
     <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 sm:p-5 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        
        <!-- Icon + Text -->
        <div class="flex items-start gap-3 flex-1">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>

            <div>
                <p class="font-semibold text-red-800 text-base sm:text-lg">
                    BELUM CHECK IN!
                </p>
                <p class="text-sm text-red-700 mt-1">
                    Anda harus melakukan check in terlebih dahulu sebelum dapat menginput panen.
                </p>
            </div>
        </div>

        <!-- Button -->
        <div class="w-full sm:w-auto">
            <a href="{{ route('attendance.index') }}"
                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto
                       bg-red-600 hover:bg-red-700 text-white px-4 py-2.5
                       rounded-lg text-sm font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
                Absen Sekarang
            </a>
        </div>

    </div>
</div>
        @endif

        {{-- TAB NAVIGATION --}}
        @if($hasMandor && !$isIzinHariIni && $sudahCheckIn && !$sudahInputHariIni && !$mandorSudahVerifikasi)
        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button onclick="showTab('input')" id="tab-input-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Input Panen
            </button>
            <button onclick="showTab('riwayat')" id="tab-riwayat-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Riwayat Panen
            </button>
        </div>
        @endif

        {{-- TAB: INPUT PANEN --}}
        <div id="tab-input" class="tab-content">
            @if($hasMandor && !$isIzinHariIni && $sudahCheckIn && !$sudahInputHariIni && !$mandorSudahVerifikasi)
                <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                    <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] bg-gradient-to-r from-[#eaf4f1] to-white">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <h2 class="text-base md:text-lg font-semibold text-gray-700">Form Input Panen Hari Ini</h2>
                        </div>
                    </div>
                    <div class="p-4 md:p-7">
                        <form action="{{ route('user.panen.store') }}" method="POST" id="panenForm">
                            @csrf

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Brondolan (Kg) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="brondolan_kg" id="brondolan_kg"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition"
                                       placeholder="Masukkan jumlah brondolan" required>
                                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Brondolan adalah buah sawit yang jatuh/terlepas dari tandan
                                </p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Jumlah Janjangan <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="janjang" id="janjang"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition"
                                       placeholder="Masukkan jumlah janjang" required>
                                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Janjang adalah tandan buah segar yang dipotong dari pohon
                                </p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Catatan Tambahan
                                </label>
                                <textarea name="catatan" id="catatan" rows="4"
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition"
                                          placeholder="Catatan tambahan (opsional)..."></textarea>
                            </div>

                            <button type="button" id="submitBtn"
                                    class="w-full bg-gradient-to-r from-[#2c5e4e] to-[#1f4a3d] hover:from-[#1f4a3d] hover:to-[#163a2e] text-white py-3 rounded-xl font-semibold transition-all shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Data Panen
                            </button>
                        </form>
                    </div>
                </div>
            @elseif($hasMandor && !$isIzinHariIni && $sudahCheckIn && $sudahInputHariIni)
                <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                    <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h2 class="text-base md:text-lg font-semibold text-gray-700">Data Panen Hari Ini</h2>
                            </div>
                            <span class="px-3 py-1 md:px-4 md:py-1.5 rounded-full bg-green-100 text-green-700 text-xs md:text-sm font-medium flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sudah Input
                            </span>
                        </div>
                    </div>
                    <div class="p-4 md:p-7">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
                            <div class="bg-gradient-to-br from-[#eaf4f1] to-[#f0f7f4] rounded-xl md:rounded-2xl p-4 md:p-6 text-center">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div class="text-xs md:text-sm text-gray-500 mb-1">Brondolan</div>
                                <div class="text-2xl md:text-4xl font-bold text-[#2c5e4e]">{{ number_format($laporanHariIni->brondolan_kg, 2) }} <span class="text-sm md:text-base font-normal">Kg</span></div>
                            </div>
                            <div class="bg-gradient-to-br from-[#eaf4f1] to-[#f0f7f4] rounded-xl md:rounded-2xl p-4 md:p-6 text-center">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div class="text-xs md:text-sm text-gray-500 mb-1">Jumlah Janjangan</div>
                                <div class="text-2xl md:text-4xl font-bold text-[#2c5e4e]">{{ number_format($laporanHariIni->janjang) }} <span class="text-sm md:text-base font-normal">Janjang</span></div>
                            </div>
                        </div>
                        @if($laporanHariIni->catatan)
                        <div class="bg-[#F8FAF9] rounded-xl p-4 md:p-5">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs md:text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan</p>
                                    <p class="text-sm md:text-base text-gray-600">{{ $laporanHariIni->catatan }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @elseif($hasMandor && !$isIzinHariIni && $sudahCheckIn && $mandorSudahVerifikasi && !$sudahInputHariIni)
                <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Input Panen Ditutup
                        </h3>
                        <p class="text-gray-600">Mandor Anda sudah memverifikasi laporan panen hari ini.</p>
                        <p class="text-sm text-gray-500 mt-2">Input panen tidak dapat dilakukan setelah verifikasi.</p>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Belum Bisa Input Panen
                        </h3>
                        <p class="text-gray-500">Silakan perhatikan peringatan di atas untuk informasi lebih lanjut.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- TAB: RIWAYAT PANEN --}}
        <div id="tab-riwayat" class="tab-content hidden">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-base md:text-lg font-semibold text-gray-700">Riwayat Panen</h2>
                    </div>
                    <div class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1.5 md:px-4 md:py-2 rounded-xl font-semibold flex items-center gap-2 text-sm md:text-base">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Total: {{ $riwayatPanen->count() }}
                    </div>
                </div>

                <div class="px-4 md:px-7 py-4 bg-gray-50 border-b border-[#E2E8F0]">
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Dari Tanggal
                            </label>
                            <input type="date" id="filterStartDate" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition bg-white">
                        </div>
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Sampai Tanggal
                            </label>
                            <input type="date" id="filterEndDate" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition bg-white">
                        </div>
                        <div>
                            <button onclick="applyFilter()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white rounded-xl text-sm font-medium transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filter
                            </button>
                        </div>
                        <div>
                            <button onclick="resetFilter()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all border border-[#E2E8F0]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-7">
                    @if($riwayatPanen->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full" id="riwayatTable">
                                <thead class="bg-[#F8FAF9] border-b border-[#E2E8F0]">
                                    <tr>
                                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Tanggal
                                        </th>
                                        <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Brondolan</th>
                                        <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Janjang</th>
                                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayatTableBody">
                                    @foreach($riwayatPanen as $item)
                                    <tr class="riwayat-row border-b border-[#E2E8F0] hover:bg-[#F8FAF9] transition" data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}">
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-semibold text-[#2c5e4e]">{{ number_format($item->brondolan_kg, 2) }} Kg</td>
                                        <td class="px-4 py-3 text-right text-sm font-semibold text-[#2c5e4e]">{{ number_format($item->janjang) }} Janjang</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">{{ $item->catatan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $riwayatPanen->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 md:py-12">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-base md:text-lg font-semibold text-gray-800">Belum Ada Riwayat Panen</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">Silakan input panen terlebih dahulu</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- INFO PANDUAN --}}
        @if($hasMandor && !$isIzinHariIni && $sudahCheckIn && !$sudahInputHariIni && !$mandorSudahVerifikasi)
        <div class="mt-5 sm:mt-6 bg-[#eaf4f1]/50 rounded-xl p-3 sm:p-4 border border-[#2c5e4e]/10">
            <div class="flex items-start gap-2 sm:gap-3">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2c5e4e] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-[#2c5e4e] flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Panduan Pengisian
                    </p>
                    <ul class="text-xs sm:text-sm text-gray-600 mt-1 space-y-0.5">
                        <li class="flex items-start gap-1">• Setiap pekerja hanya dapat menginput panen satu kali dalam sehari</li>
                        <li class="flex items-start gap-1">• Pastikan data brondolan dan janjang diisi dengan benar</li>
                        <li class="flex items-start gap-1">• Data yang sudah disimpan tidak dapat diubah pada hari yang sama</li>
                        <li class="flex items-start gap-1">• <strong class="text-red-600">Jika sedang IZIN/SAKIT yang sudah disetujui, tidak bisa input panen</strong></li>
                        <li class="flex items-start gap-1">• <strong class="text-red-600">Setelah mandor memverifikasi, Anda tidak bisa input panen lagi</strong></li>
                        <li class="flex items-start gap-1">• Riwayat panen dapat dilihat pada tab "Riwayat Panen" dan dapat difilter berdasarkan tanggal</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- MODAL KONFIRMASI (SAMA SEPERTI PATROLI) --}}
<div id="validationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden shadow-2xl">
        <div class="bg-[#2c5e4e] px-6 py-4">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Konfirmasi Data Panen
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Pastikan data berikut sudah benar:</p>
            <div id="validationSummary" class="space-y-3 max-h-96 overflow-y-auto">
                <div class="border-b border-gray-200 pb-3 mb-3">
                    <div class="font-semibold text-[#2c5e4e] mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Brondolan
                    </div>
                    <div class="text-sm text-gray-600 ml-6">
                        <span id="confirmBrondolan">-</span>
                    </div>
                </div>
                <div class="border-b border-gray-200 pb-3 mb-3">
                    <div class="font-semibold text-[#2c5e4e] mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Jumlah Janjangan
                    </div>
                    <div class="text-sm text-gray-600 ml-6">
                        <span id="confirmJanjang">-</span>
                    </div>
                </div>
                <div>
                    <div class="font-semibold text-[#2c5e4e] mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Catatan
                    </div>
                    <div class="text-sm text-gray-600 ml-6">
                        <span id="confirmCatatan">-</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-200">
                <div class="flex items-center gap-2 text-amber-700 text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Data yang sudah disimpan tidak dapat diubah pada hari yang sama</span>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" id="closeModalBtn" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">Batal</button>
                <button type="button" id="confirmSubmitBtn" class="flex-1 px-4 py-2 bg-[#2c5e4e] text-white rounded-lg hover:bg-[#1f4a3d] transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Konfirmasi & Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.tab-content {
    transition: all 0.3s ease;
}
#successMessage {
    transition: opacity 0.3s ease;
}
.modal-open {
    overflow: hidden;
}
</style>

<script>
// Ambil nilai dari form
function showValidationModal() {
    const brondolan = document.getElementById('brondolan_kg')?.value;
    const janjang = document.getElementById('janjang')?.value;
    const catatan = document.getElementById('catatan')?.value || '-';
    
    if (!brondolan || parseFloat(brondolan) <= 0) {
        alert('Silakan masukkan jumlah brondolan yang valid (minimal 0.01 Kg)');
        document.getElementById('brondolan_kg').focus();
        return false;
    }
    
    if (!janjang || parseInt(janjang) <= 0) {
        alert('Silakan masukkan jumlah janjangan yang valid (minimal 1 Janjang)');
        document.getElementById('janjang').focus();
        return false;
    }
    
    document.getElementById('confirmBrondolan').innerHTML = parseFloat(brondolan).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' Kg';
    document.getElementById('confirmJanjang').innerHTML = parseInt(janjang).toLocaleString('id-ID') + ' Janjang';
    document.getElementById('confirmCatatan').innerHTML = catatan;
    
    const modal = document.getElementById('validationModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    return true;
}

function closeModal() {
    const modal = document.getElementById('validationModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function submitForm() {
    const modal = document.getElementById('validationModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    const form = document.getElementById('panenForm');
    if (form) {
        const submitBtn = document.getElementById('confirmSubmitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        }
        form.submit();
    }
}

function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`tab-${tab}`).classList.remove('hidden');

    const inputBtn = document.getElementById('tab-input-btn');
    const riwayatBtn = document.getElementById('tab-riwayat-btn');

    if (tab === 'input') {
        if (inputBtn) {
            inputBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            inputBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        }
        if (riwayatBtn) {
            riwayatBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            riwayatBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        }
    } else {
        if (riwayatBtn) {
            riwayatBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            riwayatBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        }
        if (inputBtn) {
            inputBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            inputBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        }
    }
}

function applyFilter() {
    const startDate = document.getElementById('filterStartDate').value;
    const endDate = document.getElementById('filterEndDate').value;
    const rows = document.querySelectorAll('#riwayatTableBody .riwayat-row');
    
    rows.forEach(row => {
        const rowDate = row.getAttribute('data-tanggal');
        let show = true;
        if (startDate && rowDate < startDate) show = false;
        if (endDate && rowDate > endDate) show = false;
        row.style.display = show ? '' : 'none';
    });
    
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    let emptyMessage = document.getElementById('emptyFilterMessage');
    
    if (visibleRows.length === 0 && rows.length > 0) {
        if (!emptyMessage) {
            const tbody = document.getElementById('riwayatTableBody');
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyFilterMessage';
            emptyRow.innerHTML = '<td colspan="4"><div class="text-center py-8"><svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg><h3 class="text-base font-semibold text-gray-700 mb-2">Tidak Ada Data</h3><p class="text-sm text-gray-500">Tidak ditemukan data panen dalam rentang tanggal yang dipilih</p></div></td>';
            tbody.appendChild(emptyRow);
        }
    } else if (emptyMessage) {
        emptyMessage.remove();
    }
}

function resetFilter() {
    document.getElementById('filterStartDate').value = '';
    document.getElementById('filterEndDate').value = '';
    const rows = document.querySelectorAll('#riwayatTableBody .riwayat-row');
    rows.forEach(row => row.style.display = '');
    const emptyMessage = document.getElementById('emptyFilterMessage');
    if (emptyMessage) emptyMessage.remove();
}

document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash;
    if (hash === '#riwayat') {
        showTab('riwayat');
    } else {
        showTab('input');
    }
    
    // Auto hide success message after 5 seconds
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }, 5000);
    }
    
    // Submit button handler
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showValidationModal();
        });
    }
    
    // Modal handlers
    const closeModalBtn = document.getElementById('closeModalBtn');
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    const modal = document.getElementById('validationModal');
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    
    if (confirmSubmitBtn) {
        confirmSubmitBtn.addEventListener('click', submitForm);
    }
    
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
});
</script>
@endsection