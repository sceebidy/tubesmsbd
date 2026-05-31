@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

        {{-- ============================================================ --}}
        {{-- HEADER SECTION --}}
        {{-- ============================================================ --}}
        <div class="mb-8 pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-0.5">Dashboard</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Kantoran</h1>
                       
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
                    <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1">
                        PT. Sipirok Indah
                    </span>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ALERT MESSAGES --}}
        {{-- ============================================================ --}}
        @if(session('success'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#e8f5f0] border border-[#2e7d5e]/20 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#2e7d5e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm md:text-base text-[#1f4a3d]">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#FDECEA] border border-[#C0392B]/20 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#C0392B] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm md:text-base text-[#7B1C14]">{{ session('error') }}</p>
        </div>
        @endif

        @if(session('warning'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="flex-1">
                <p class="font-semibold text-amber-800 text-sm">Peringatan</p>
                <p class="text-sm text-amber-700">{!! session('warning') !!}</p>
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- INFO BANNER IZIN/SAKIT HARI INI --}}
        {{-- ============================================================ --}}
        @if(isset($isIzinHariIni) && $isIzinHariIni)
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-blue-800">
                        @if($izinStatus == 'izin')
                            Anda sedang IZIN pada hari ini
                        @else
                            Anda sedang SAKIT pada hari ini
                        @endif
                    </p>
                    <p class="text-sm text-blue-700">
                        Pengajuan Anda telah disetujui. Anda tidak perlu melakukan absensi hari ini.
                        Status akan otomatis tercatat di riwayat absensi.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- ROW 1: STAT CARDS (2 CARD - SAMA SEPERTI CLEANING) --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            
            {{-- Card Kiri: Shift Masuk --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Shift Masuk</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">08:00</p>
                        <p class="text-xs text-gray-400 mt-2">WIB</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card Kanan: Kehadiran Bulan Ini --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kehadiran Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $monthlyCount ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-2">Hari kerja</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- ROW 2: MAIN GRID --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KEHADIRAN DETAIL - 2/3 (SAMA SEPERTI CLEANING) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="px-7 py-5 border-b-2 border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-700">Status Kehadiran Hari Ini</h2>
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
                                <p class="text-sm text-gray-500">Pengajuan telah disetujui. Tidak perlu melakukan absensi.</p>
                                <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Disetujui
                                </div>
                            </div>
                        @elseif(!empty($absenHariIni) && $absenHariIni->check_in)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Check In Card --}}
                                <div class="bg-[#eaf4f1] rounded-xl p-6 border border-[#2c5e4e]/20 text-center">
                                    <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center mx-auto mb-4 shadow-sm">
                                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Waktu Masuk</p>
                                    <p class="text-3xl font-bold text-gray-800 mb-2">{{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i:s') }}</p>
                                    @php
                                        $batasWaktu = \Carbon\Carbon::createFromTime(8, 0, 0);
                                        $checkInTime = \Carbon\Carbon::parse($absenHariIni->check_in);
                                        $isTerlambat = $checkInTime->gt($batasWaktu);
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $isTerlambat ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($isTerlambat)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            @endif
                                        </svg>
                                        {{ $isTerlambat ? 'Terlambat' : 'Tepat Waktu' }}
                                    </span>
                                </div>

                                {{-- Check Out Card --}}
                                <div class="bg-[#eaf4f1] rounded-xl p-6 border border-[#2c5e4e]/20 text-center">
                                    <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center mx-auto mb-4 shadow-sm">
                                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Waktu Pulang</p>
                                    @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                        <p class="text-3xl font-bold text-gray-800 mb-2">{{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i:s') }}</p>
                                        @php
                                            $batasPulang = \Carbon\Carbon::createFromTime(17, 0, 0);
                                            $checkOutTime = \Carbon\Carbon::parse($absenHariIni->check_out);
                                            $isTooEarly = $checkOutTime->lt($batasPulang);
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold
                                            {{ $isTooEarly ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($isTooEarly)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                @endif
                                            </svg>
                                            {{ $isTooEarly ? 'Terlalu Cepat' : 'Tepat Waktu' }}
                                        </span>
                                    @else
                                        <div class="py-6">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-gray-500">Belum Check Out</p>
                                            <p class="text-xs text-gray-400 mt-1">Anda masih dalam sesi kerja</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            {{-- TAMPILAN BELUM ABSEN (SAMA SEPERTI CLEANING SERVICE) --}}
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Belum Ada Absensi Hari Ini</h3>
                                <p class="text-sm text-gray-500 mt-1">Silakan buka halaman absensi untuk check in</p>
                                <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 mt-4 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Absen Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- AKSI CEPAT - 1/3 --}}
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
                                    <p class="text-sm text-blue-600">Pengajuan telah disetujui. Tidak perlu absen.</p>
                                </div>

                            {{-- TAMPILKAN TOMBOL ABSEN HANYA JIKA TIDAK SEDANG IZIN/SAKIT --}}
                            @else
                                @if(!isset($absenHariIni) || !$absenHariIni || !$absenHariIni->check_in)
                                <a href="{{ route('attendance.index') }}"
                                   class="inline-flex items-center justify-center gap-3 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Absen Masuk</span>
                                </a>
                                @endif

                                @if(isset($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
                                <a href="{{ route('attendance.index') }}"
                                    class="inline-flex items-center justify-center gap-3 bg-[#d4a373] hover:bg-[#b88352] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Ambil Foto & Absen Pulang</span>
                                </a>
                                @endif

                                {{-- TAMPILKAN INFORMASI SUDAH CHECKOUT --}}
                                @if(isset($absenHariIni) && $absenHariIni->check_out)
                                <div class="bg-[#eaf4f1] rounded-xl p-6 text-center border border-[#2c5e4e]/20">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <h3 class="text-base font-semibold text-[#1f4a3d] mb-2">Absensi Selesai</h3>
                                    <p class="text-sm text-gray-600">Terima kasih sudah bekerja hari ini!</p>
                                </div>
                                @endif
                            @endif

                            {{-- TOMBOL RIWAYAT --}}
                            <a href="{{ route('attendance.history') }}"
                               class="inline-flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3.5 rounded-xl font-medium transition-all hover:translate-y-[-2px] border border-gray-200 w-full">
                                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Lihat Riwayat</span>
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
// ── CLOCK ───────────────────────────────────────────
let currentTime = new Date("{{ now('Asia/Jakarta') }}");

function updateClock() {
    currentTime.setSeconds(currentTime.getSeconds() + 1);
    const el = document.getElementById('realtimeClock');
    if (el) el.textContent = currentTime.toLocaleTimeString('id-ID', { hour12: false });
}
setInterval(updateClock, 1000);
updateClock();
</script>
@endsection