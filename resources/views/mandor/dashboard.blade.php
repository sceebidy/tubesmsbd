@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-0.5">Dashboard</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Mandor</h1>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, j F Y') }}</p>
                    <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1">
                        PT. Sipirok Indah
                    </span>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-5 py-4 rounded-r-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-5 py-4 rounded-r-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {!! session('error') !!}
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 text-amber-700 px-5 py-4 rounded-r-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ session('warning') }}
                </div>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- VALIDASI IZIN/SAKIT HARI INI --}}
        {{-- ============================================================ --}}
        @if(isset($isIzinHariIni) && $isIzinHariIni)
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-blue-800 text-lg">
                        @if($izinStatus == 'izin')
                            Anda sedang IZIN pada hari ini
                        @else
                            Anda sedang SAKIT pada hari ini
                        @endif
                    </p>
                    <p class="text-sm text-blue-700 mt-1">
                        Pengajuan Anda telah disetujui. Anda tetap dapat melakukan verifikasi panen anggota.
                        Status kehadiran akan otomatis tercatat di riwayat absensi.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- CEK STATUS VERIFIKASI PANEN MANDOR --}}
        @php
            $today = \Carbon\Carbon::today('Asia/Jakarta');
            $pekerjaList = \App\Models\User::where('mandor_id', Auth::id())
                ->where('role', 'user')
                ->get();
            
            $pekerjaIds = $pekerjaList->pluck('id')->toArray();
            $sudahPanen = \App\Models\LaporanPanen::whereIn('pekerja_id', $pekerjaIds)
                ->whereDate('tanggal', $today)
                ->pluck('pekerja_id')
                ->toArray();
            
            $sudahDiverifikasi = \App\Models\LaporanPanen::whereIn('pekerja_id', $pekerjaIds)
                ->whereDate('tanggal', $today)
                ->where('status', 'diverifikasi_mandor')
                ->pluck('pekerja_id')
                ->toArray();
            
            $belumDiverifikasi = array_diff($sudahPanen, $sudahDiverifikasi);
            $perluVerifikasi = count($belumDiverifikasi) > 0 && count($sudahPanen) > 0;
        @endphp

        {{-- Peringatan jika belum verifikasi panen --}}
        @if($perluVerifikasi && !empty($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out && (!isset($isIzinHariIni) || !$isIzinHariIni))
        <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 text-amber-700 px-5 py-4 rounded-r-xl shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <p class="font-semibold">Perhatian!</p>
                    <p class="text-sm mt-1">Anda memiliki <strong>{{ count($belumDiverifikasi) }}</strong> laporan panen yang belum diverifikasi. 
                    Silakan verifikasi laporan panen sebelum checkout.</p>
                    <a href="{{ route('mandor.panen') }}" class="inline-block mt-3 text-sm font-semibold text-amber-800 hover:text-amber-900 underline">
                        Klik di sini untuk verifikasi panen →
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ROW 1: STAT CARDS (HANYA 3 CARD) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">

            {{-- Total Anggota Card --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Anggota</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPekerja }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Hadir Hari Ini Card --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Anggota Hadir Hari Ini</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $pekerjaHadir }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Tepat Waktu: {{ $pekerjaTepatWaktu }}</span>
                        <span>Terlambat: {{ $pekerjaTerlambat }}</span>
                    </div>
                </div>
            </div>

            
            {{-- Kehadiran Bulan Ini Card --}}
<div class="bg-[#2c5e4e] rounded-2xl p-5 transition-all hover:shadow-md">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-medium text-white/70 uppercase tracking-wide">Kehadiran Bulan Ini</p>
            <p class="text-3xl font-bold text-white mt-1">
                {{ $monthlyCount }}
            </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>
    <div class="mt-3 pt-3 border-t border-white/20">
      
    </div>
</div>

        </div>

        {{-- ROW 2: MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Detail Kehadiran Mandor - 2/3 kolom --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="px-7 py-5 border-b-2 border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-700">Status Kehadiran Anda</h2>
                    </div>
                    <div class="p-7">
                        {{-- VALIDASI IZIN/SAKIT DI STATUS KEHADIRAN --}}
                        @if(isset($isIzinHariIni) && $isIzinHariIni)
                            <div class="text-center py-8">
                                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-blue-700 mb-2">
                                    @if($izinStatus == 'izin')
                                        IZIN
                                    @else
                                        SAKIT
                                    @endif
                                </h3>
                                <p class="text-gray-600 mb-2">Anda sedang {{ $izinStatus == 'izin' ? 'IZIN' : 'SAKIT' }} pada hari ini</p>
                                <p class="text-sm text-gray-500">Pengajuan telah disetujui. Anda tetap dapat melakukan verifikasi panen.</p>
                                <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Disetujui
                                </div>
                            </div>
                        @elseif($absenHariIni)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 transition-all hover:shadow-md">
                                    <div class="w-16 h-16 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Check In</p>
                                    <p class="text-3xl font-bold text-gray-800 mb-3">
                                        {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                                    </p>
                                    <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                        {{ $absenHariIni->status == 'tepat waktu' ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-red-100 text-red-700' }}">
                                        {{ $absenHariIni->status == 'tepat waktu' ? 'Tepat Waktu' : 'Terlambat' }}
                                    </span>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 transition-all hover:shadow-md">
                                    <div class="w-16 h-16 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Check Out</p>
                                    <p class="text-3xl font-bold text-gray-800 mb-3">
                                        @if($absenHariIni->check_out)
                                            {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                                        @else
                                            --
                                        @endif
                                    </p>
                                    
                                    @if($absenHariIni->check_out)
                                    <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                        {{ $isCheckoutTooEarly ? 'bg-amber-100 text-amber-700' : 'bg-[#eaf4f1] text-[#2c5e4e]' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($isCheckoutTooEarly)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            @endif
                                        </svg>
                                        {{ $isCheckoutTooEarly ? 'Terlalu Cepat' : 'Selesai' }}
                                    </span>
                                    @endif
                                    
                                    @if(!$absenHariIni->check_out)
                                        @if($perluVerifikasi)
                                            <a href="{{ route('mandor.panen') }}" 
                                               class="inline-block mt-3 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                                Verifikasi Panen Dulu
                                            </a>
                                        @else
                                            <a href="{{ route('attendance.index') }}" 
                                               class="inline-block mt-3 bg-[#d4a373] hover:bg-[#b88352] text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                                Absen Pulang
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-20 h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 mb-4">Anda belum melakukan absen hari ini</p>
                                <a href="{{ route('attendance.index') }}" 
                                   class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-2.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md inline-block">
                                    Absen Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Aksi Cepat - 1/3 kolom --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="px-7 py-5 border-b-2 border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700">Aksi Cepat</h3>
                    </div>
                    <div class="p-7">
                        <div class="flex flex-col gap-4">

                            {{-- TOMBOL LAPORAN PANEN (TETAP BISA AKSES MESKIPUN IZIN) --}}
                            <a href="{{ route('mandor.panen') }}"
                               class="inline-flex items-center justify-center gap-3 bg-[#eaf4f1] hover:bg-[#d4e8e0] text-[#2c5e4e] px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] border border-[#2c5e4e]/20 w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Laporan Panen</span>
                            </a>

                            {{-- JIKA SEDANG IZIN/SAKIT --}}
                            @if(isset($isIzinHariIni) && $isIzinHariIni)
                                <div class="bg-blue-50 rounded-xl p-6 text-center border border-blue-200">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-base font-semibold text-blue-700 mb-2">
                                        @if($izinStatus == 'izin')
                                            Izin Hari Ini
                                        @else
                                            Sakit Hari Ini
                                        @endif
                                    </h3>
                                    <p class="text-sm text-blue-600">Pengajuan telah disetujui. Tetap bisa verifikasi panen.</p>
                                </div>

                            {{-- BELUM ABSEN MASUK --}}
                            @elseif(!$absenHariIni)
                                <a href="{{ route('attendance.index') }}"
                                   class="inline-flex items-center justify-center gap-3 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Absen Masuk</span>
                                </a>

                            {{-- SUDAH ABSEN MASUK, BELUM CHECKOUT --}}
                            @elseif($absenHariIni && $absenHariIni->check_in && !$absenHariIni->check_out)
                                @if($perluVerifikasi)
                                    <a href="{{ route('mandor.panen') }}"
                                       class="inline-flex items-center justify-center gap-3 bg-amber-500 hover:bg-amber-600 text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span>Verifikasi Laporan Panen</span>
                                    </a>
                                @else
                                    <a href="{{ route('attendance.index') }}"
                                       class="inline-flex items-center justify-center gap-3 bg-[#d4a373] hover:bg-[#b88352] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Ambil Foto & Absen Pulang</span>
                                    </a>
                                @endif

                            {{-- SUDAH CHECKOUT --}}
                            @elseif($absenHariIni && $absenHariIni->check_out)
                                <div class="bg-[#eaf4f1] rounded-xl p-6 text-center border border-[#2c5e4e]/20">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <h3 class="text-base font-semibold text-[#1f4a3d] mb-2">Absensi Selesai</h3>
                                    <p class="text-sm text-gray-600">Terima kasih sudah bekerja hari ini!</p>
                                </div>
                            @endif

                            {{-- TOMBOL RIWAYAT --}}
                            <a href="{{ route('attendance.history') }}"
                               class="inline-flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3.5 rounded-xl font-medium transition-all hover:translate-y-[-2px] border border-gray-200 w-full">
                                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Lihat Riwayat Absen</span>
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    {{-- TABEL DETAIL ANGGOTA --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mt-6">
    <div class="px-7 py-5 border-b border-[#eaf4f1] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-700">Daftar Anggota & Status Kehadiran</h2>
        </div>
        
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Nama Pekerja
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Check In
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Check Out
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($detailAnggota as $anggota)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#eaf4f1] flex items-center justify-center">
                                <span class="text-sm font-bold text-[#2c5e4e]">{{ strtoupper(substr($anggota['name'], 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $anggota['name'] }}</p>
                                <p class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Pekerja Sawit
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $badgeClass = match($anggota['status_color']) {
                                'green' => 'bg-green-100 text-green-700',
                                'orange' => 'bg-orange-100 text-orange-700',
                                'blue' => 'bg-blue-100 text-blue-700',
                                'purple' => 'bg-purple-100 text-purple-700',
                                'red' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-600'
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            @if($anggota['status_kehadiran'] == 'tepat_waktu')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Hadir
                            @elseif($anggota['status_kehadiran'] == 'terlambat')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Terlambat
                            @elseif($anggota['status_kehadiran'] == 'izin')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Izin
                            @elseif($anggota['status_kehadiran'] == 'sakit')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Sakit
                            @elseif($anggota['status_kehadiran'] == 'alpa')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Alpa
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Absen
                            @endif
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($anggota['check_in_time'])
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium text-gray-800">{{ $anggota['check_in_time'] }}</span>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    WIB
                                </span>
                            </div>
                        @else
                            <span class="text-sm text-gray-400">--:--:--</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($anggota['check_out_time'])
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium text-gray-800">{{ $anggota['check_out_time'] }}</span>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    WIB
                                </span>
                            </div>
                        @else
                            <span class="text-sm text-gray-400">--:--:--</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada anggota yang ditugaskan</p>
                            <p class="text-xs text-gray-400 mt-1">Silakan hubungi admin untuk menambahkan pekerja</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-7 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="text-xs text-gray-500">Total Anggota: <span class="font-semibold text-gray-700">{{ $totalPekerja }}</span></p>
        </div>
        <div class="flex gap-3">
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-xs text-gray-600">Hadir: <span class="font-semibold">{{ $pekerjaTepatWaktu }}</span></span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs text-gray-600">Terlambat: <span class="font-semibold">{{ $pekerjaTerlambat }}</span></span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-xs text-gray-600">Izin: <span class="font-semibold">{{ $pekerjaIzin }}</span></span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs text-gray-600">Sakit: <span class="font-semibold">{{ $pekerjaSakit }}</span></span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs text-gray-600">Alpa: <span class="font-semibold">{{ $pekerjaAlpa }}</span></span>
            </div>
        </div>
    </div>
</div>
</div>

    
</div>
@endsection