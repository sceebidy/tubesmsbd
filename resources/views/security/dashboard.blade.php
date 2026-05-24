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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Security</h1>
                        <p class="text-sm text-gray-500 mt-1">Pos Keamanan & Monitoring Area</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1">
                            PT. Sipirok Indah
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ALERT MESSAGES --}}
        {{-- ============================================================ --}}
        @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-[#e8f5f0] border border-[#2e7d5e]/20 flex items-center gap-3" id="successMessage">
            <svg class="w-5 h-5 text-[#2e7d5e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-[#1f4a3d] flex-1">{{ session('success') }}</p>
            <button type="button" onclick="document.getElementById('successMessage').remove()" class="text-[#2c5e4e]/60 hover:text-[#2c5e4e] text-xl leading-none">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-[#FDECEA] border border-[#C0392B]/20 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#C0392B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-[#7B1C14]">{{ session('error') }}</p>
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
                        Pengajuan Anda telah disetujui. Anda tidak perlu melakukan absensi hari ini.
                        Status akan otomatis tercatat di riwayat absensi.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- STAT CARDS --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Shift Masuk</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">18:00</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Patroli Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPatroliHariIni ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Waktu Masuk</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                        {{ !empty($absenHariIni) && $absenHariIni->check_in ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-500' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                        @if(!empty($absenHariIni) && $absenHariIni->check_in) Tepat Waktu @else Belum Masuk @endif
                    </span>
                </div>
            </div>

            <div class="bg-[#2c5e4e] rounded-2xl p-5 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-white/70 uppercase tracking-wide">Waktu Pulang</p>
                        <p class="text-3xl font-bold text-white mt-1">
                            @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-white/20">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                        {{ !empty($absenHariIni) && $absenHariIni->check_out ? 'bg-white/20 text-white' : 'bg-white/10 text-white/60' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                        @if(!empty($absenHariIni) && $absenHariIni->check_out) Selesai @else Belum Pulang @endif
                    </span>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- MAIN GRID --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KEHADIRAN DETAIL - 2/3 --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden h-full">
                    <div class="px-7 py-5 border-b border-[#eaf4f1] flex items-center gap-3">
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
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-xl p-6 text-center border border-[#E2E8F0] transition-all hover:shadow-md">
                                    <div class="w-14 h-14 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Waktu Masuk</p>
                                    <p class="text-3xl font-bold text-gray-800 mb-3">
                                        @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                            {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                                        @else --
                                        @endif
                                    </p>
                                    <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                        {{ !empty($absenHariIni) && $absenHariIni->check_in ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                        @if(!empty($absenHariIni) && $absenHariIni->check_in) Tepat Waktu @else Belum @endif
                                    </span>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-6 text-center border border-[#E2E8F0] transition-all hover:shadow-md">
                                    <div class="w-14 h-14 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Waktu Pulang</p>
                                    <p class="text-3xl font-bold text-gray-800 mb-3">
                                        @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                            {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                                        @else --
                                        @endif
                                    </p>
                                    <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                        {{ !empty($absenHariIni) && $absenHariIni->check_out ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                        @if(!empty($absenHariIni) && $absenHariIni->check_out) Selesai @else Belum Pulang @endif
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- AKSI CEPAT - 1/3 --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden h-full">
                    <div class="px-7 py-5 border-b border-[#eaf4f1] flex items-center gap-3">
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
                                    <svg class="w-14 h-14 mx-auto mb-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                            {{-- SUDAH CHECKOUT --}}
                            @elseif(isset($absenHariIni) && $absenHariIni->check_out)
                                <div class="bg-[#eaf4f1] rounded-xl p-6 text-center border border-[#2c5e4e]/20 flex-1 flex flex-col items-center justify-center">
                                    <svg class="w-14 h-14 mx-auto mb-3 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-[#1f4a3d] mb-2">Absensi Selesai</h3>
                                    <p class="text-sm text-gray-600">Terima kasih sudah menjaga wilayah perkebunan hari ini!</p>
                                </div>

                            {{-- TOMBOL ABSEN --}}
                            @else
                                <a href="{{ route('attendance.history') }}"
                                    class="inline-flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3.5 rounded-xl font-medium transition-all border border-[#E2E8F0] w-full">
                                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Lihat Riwayat
                                </a>

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
                            @endif

                            {{-- TOMBOL INPUT PATROLI --}}
                            @if(!isset($isIzinHariIni) || !$isIzinHariIni)
                            <a href="{{ route('security.patroli') }}"
                               class="inline-flex items-center justify-center gap-3 bg-[#eaf4f1] hover:bg-[#d4e8e0] text-[#2c5e4e] px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] border border-[#2c5e4e]/20 w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>Input Laporan Patroli</span>
                            </a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function previewPhotos(event) {
    const preview = document.getElementById('photoPreview');
    const counter = document.getElementById('fileCounter');
    const files = event.target.files;
    preview.innerHTML = '';
    const maxFiles = 5;
    const selectedFiles = files.length > maxFiles ? Array.from(files).slice(0, maxFiles) : files;
    if (files.length > maxFiles) {
        alert(`Maksimal ${maxFiles} foto yang dapat diunggah. Hanya ${maxFiles} foto pertama yang akan diproses.`);
    }
    counter.textContent = `${selectedFiles.length} foto terpilih`;
    Array.from(selectedFiles).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'relative';
            previewItem.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-200" alt="Preview ${index + 1}">
                <button type="button" onclick="removePhoto(${index})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">×</button>
            `;
            preview.appendChild(previewItem);
        }
        reader.readAsDataURL(file);
    });
}

function previewPhotos2(event) {
    const preview = document.getElementById('photoPreview2');
    const counter = document.getElementById('fileCounter2');
    const files = event.target.files;
    preview.innerHTML = '';
    const maxFiles = 5;
    const selectedFiles = files.length > maxFiles ? Array.from(files).slice(0, maxFiles) : files;
    if (files.length > maxFiles) {
        alert(`Maksimal ${maxFiles} foto yang dapat diunggah. Hanya ${maxFiles} foto pertama yang akan diproses.`);
    }
    counter.textContent = `${selectedFiles.length} foto terpilih`;
    Array.from(selectedFiles).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'relative';
            previewItem.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-200" alt="Preview ${index + 1}">
                <button type="button" onclick="removePhoto2(${index})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">×</button>
            `;
            preview.appendChild(previewItem);
        }
        reader.readAsDataURL(file);
    });
}

function removePhoto(index) {
    const input = document.getElementById('photosInput');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    files.splice(index, 1);
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;
    previewPhotos({ target: input });
}

function removePhoto2(index) {
    const input = document.getElementById('photosInput2');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    files.splice(index, 1);
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;
    previewPhotos2({ target: input });
}

document.addEventListener('DOMContentLoaded', function() {
    const form1 = document.getElementById('uploadForm');
    const form2 = document.getElementById('uploadForm2');
    if (form1) {
        form1.addEventListener('submit', function() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<svg class="w-5 h-5 animate-spin mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengunggah...';
        });
    }
    if (form2) {
        form2.addEventListener('submit', function() {
            document.getElementById('loading2').classList.remove('hidden');
            document.getElementById('submitBtn2').disabled = true;
            document.getElementById('submitBtn2').innerHTML = '<svg class="w-5 h-5 animate-spin mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengunggah...';
        });
    }
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }, 5000);
    }
});
</script>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.animate-bounce {
    animation: bounce 0.5s ease-in-out infinite;
}
#successMessage {
    transition: opacity 0.3s ease;
}
</style>
@endsection