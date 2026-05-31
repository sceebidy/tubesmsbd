@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-4 md:p-8">
    <div class="container mx-auto max-w-6xl px-2 sm:px-6">

        {{-- HEADER SECTION --}}
        <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-[#eaf4f1] rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
</svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-0.5">Cleaning Service</p>
                        <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Sistem Kinerja Cleaning</h1>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs md:text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
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
            <p class="text-sm md:text-base text-[#1f4a3d] flex-1">{{ session('success') }}</p>
            <button type="button" onclick="document.getElementById('successMessage').remove()" class="text-[#2c5e4e]/60 hover:text-[#2c5e4e] text-xl leading-none">&times;</button>
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

        {{-- PERINGATAN BELUM CHECK-IN --}}
        @if(isset($sudahCheckIn) && !$sudahCheckIn)
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-red-800 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Belum Melakukan Check-in!
                    </p>
                    <p class="text-sm text-red-700 mt-1">
                        Anda harus melakukan CHECK-IN terlebih dahulu sebelum dapat menginput kinerja cleaning.
                        Silakan lakukan check-in melalui halaman absensi.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- VALIDASI IZIN/SAKIT HARI INI --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && isset($isIzinHariIni) && $isIzinHariIni)
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-blue-800 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        @if($izinStatus == 'izin')
                            Anda sedang IZIN pada hari ini
                        @else
                            Anda sedang SAKIT pada hari ini
                        @endif
                    </p>
                    <p class="text-sm text-blue-700 mt-1">
                        Pengajuan Anda telah disetujui. Anda tidak perlu menginput kinerja cleaning pada hari ini.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- PERINGATAN JIKA SUDAH INPUT KINERJA HARI INI --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && isset($sudahInputHariIni) && $sudahInputHariIni && (!isset($isIzinHariIni) || !$isIzinHariIni))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-green-800 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Kinerja Hari Ini Sudah Diinput
                    </p>
                    <p class="text-sm text-green-700 mt-1">
                        Anda sudah menginput kinerja cleaning untuk hari ini. 
                        Tidak dapat menginput ulang. Silakan lanjutkan ke checkout.
                    </p>
                </div>
                <div>
                    <a href="{{ route('attendance.index') }}" 
                       class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- TAB NAVIGATION --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && (!isset($isIzinHariIni) || !$isIzinHariIni) && (!isset($sudahInputHariIni) || !$sudahInputHariIni))
        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button onclick="showTab('input')" id="tab-input-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Input Kinerja
            </button>
            <button onclick="showTab('riwayat')" id="tab-riwayat-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Riwayat Hari Ini
            </button>
        </div>
        @endif

        {{-- TAB: INPUT KINERJA --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && (!isset($isIzinHariIni) || !$isIzinHariIni) && (!isset($sudahInputHariIni) || !$sudahInputHariIni))
        <div id="tab-input" class="tab-content">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-base md:text-lg font-semibold text-gray-700">Form Laporan Pekerjaan</h2>
                    </div>
                </div>
                <div class="p-4 md:p-7">
                    <form action="{{ route('cleaning.kinerja.store') }}" method="POST" id="kinerjaForm">
                        @csrf
                        <div id="wrapper" class="space-y-5 sm:space-y-6">
                            <div class="item bg-[#F8FAF9] rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] relative">
                                <div class="flex items-center gap-2 mb-3 sm:mb-4 pb-2 border-b border-[#E2E8F0]">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#2c5e4e] flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">1</span>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-500">Area Pekerjaan</span>
                                </div>

                                <div class="mb-4 sm:mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Area <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="area[]" class="area-input w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" placeholder="Contoh: Area Lobby, Toilet Lt.2" required>
                                </div>

                                <div class="mb-4 sm:mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Keterangan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="keterangan[]" required class="keterangan-input w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" rows="3" placeholder="Deskripsi pekerjaan..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Foto Bukti <span class="text-red-500">*</span>
                                    </label>
                                    <div class="video-container relative rounded-xl overflow-hidden bg-gray-900 w-full md:max-w-md mx-auto">
                                        <div class="aspect-[4/3]">
                                            <video class="camera-video w-full h-full object-cover" autoplay playsinline muted></video>
                                            <canvas class="hidden"></canvas>
                                            <button type="button" class="take-photo-btn absolute bottom-3 left-1/2 -translate-x-1/2 bg-white/95 hover:bg-white text-[#2c5e4e] font-semibold px-4 py-1.5 rounded-full shadow-lg transition-all text-sm">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Ambil Foto
                                            </button>
                                        </div>
                                    </div>
                                    <div class="photo-preview-container hidden mt-3">
                                        <div class="bg-[#eaf4f1] rounded-xl p-3 flex items-center gap-3">
                                            <img class="photo-preview w-16 h-16 rounded-xl object-cover border-2 border-white shadow" src="" alt="Preview">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-800 text-sm">Foto berhasil diambil</p>
                                                <button type="button" class="retake-photo-btn text-xs text-[#d4a373] hover:text-[#b88352] font-medium">
                                                    <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Ambil Ulang
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="foto[]" class="foto-input" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-[#E2E8F0]">
                            <button type="button" id="tambahAreaBtn" class="inline-flex items-center gap-2 bg-white border border-[#2c5e4e] text-[#2c5e4e] hover:bg-[#eaf4f1] px-4 py-2 rounded-xl font-semibold transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Area
                            </button>
                            <button type="button" id="submitBtn" class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2 rounded-xl font-semibold shadow-md transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- TAMPILKAN RIWAYAT --}}
        @php
            $showRiwayatOnly = (isset($sudahCheckIn) && $sudahCheckIn) && ((isset($isIzinHariIni) && $isIzinHariIni) || (isset($sudahInputHariIni) && $sudahInputHariIni));
        @endphp

        @if(isset($sudahCheckIn) && $sudahCheckIn)
        <div id="tab-riwayat" class="@if(!$showRiwayatOnly) tab-content hidden @endif">
            <div class="bg-white rounded-xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 border-b border-[#eaf4f1] flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-base font-semibold text-gray-700">Riwayat Kinerja Hari Ini</h2>
                    </div>
                    <span class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1 rounded-xl font-semibold text-sm">
                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Total: {{ $riwayatHariIni->count() }}
                    </span>
                </div>
                <div class="p-4 md:p-7">
                    @if($riwayatHariIni->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($riwayatHariIni as $item)
                            <div class="border border-[#E2E8F0] rounded-xl overflow-hidden bg-white transition-all hover:shadow-md">
                                <img src="{{ asset('storage/' . $item->foto) }}" onerror="this.src='https://placehold.co/600x400?text=No+Image'" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        {{ $item->area }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-2">{{ $item->keterangan }}</p>
                                    <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500">Belum ada kinerja hari ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- TOMBOL KEMBALI KE ABSENSI --}}
        @if((isset($showRiwayatOnly) && $showRiwayatOnly) || (isset($sudahCheckIn) && !$sudahCheckIn))
        <div class="mt-6 text-center">
            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Kembali ke Halaman Absensi
            </a>
        </div>
        @endif

    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="validationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden shadow-2xl">
        <div class="bg-[#2c5e4e] px-6 py-4">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Konfirmasi Data Kinerja
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Pastikan data berikut sudah benar:</p>
            <div id="validationSummary" class="space-y-4 max-h-96 overflow-y-auto"></div>
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
let videoStreams = new Map();

async function initCamera(videoElement) {
    if (!videoElement) return;
    if (videoElement.srcObject) {
        videoElement.srcObject.getTracks().forEach(track => track.stop());
    }
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'user' }, 
            audio: false 
        });
        videoElement.srcObject = stream;
        videoStreams.set(videoElement, stream);
        await new Promise((resolve) => {
            videoElement.onloadedmetadata = () => {
                videoElement.play();
                resolve();
            };
        });
    } catch (err) {
        console.error('Kamera error:', err);
    }
}

function takePhoto(btn) {
    const item = btn.closest('.item');
    const video = item.querySelector('.camera-video');
    const canvas = item.querySelector('canvas');
    const fotoInput = item.querySelector('.foto-input');
    const previewContainer = item.querySelector('.photo-preview-container');
    const previewImg = item.querySelector('.photo-preview');
    const videoContainer = item.querySelector('.video-container');
    
    if (!video || !video.videoWidth || video.videoWidth === 0) {
        alert('Kamera belum siap. Tunggu sebentar.');
        return;
    }
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL('image/jpeg', 0.85);
    fotoInput.value = dataURL;
    previewImg.src = dataURL;
    previewContainer.classList.remove('hidden');
    videoContainer.classList.add('hidden');
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
        video.srcObject = null;
    }
}

function retakePhoto(btn) {
    const item = btn.closest('.item');
    const video = item.querySelector('.camera-video');
    const fotoInput = item.querySelector('.foto-input');
    const previewContainer = item.querySelector('.photo-preview-container');
    const videoContainer = item.querySelector('.video-container');
    fotoInput.value = '';
    previewContainer.classList.add('hidden');
    videoContainer.classList.remove('hidden');
    initCamera(video);
}

function tambahForm() {
    const wrapper = document.getElementById('wrapper');
    const currentCount = document.querySelectorAll('.item').length;
    const newNumber = currentCount + 1;
    
    const newItem = document.createElement('div');
    newItem.className = 'item bg-[#F8FAF9] rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] relative';
    newItem.innerHTML = `
        <button type="button" class="hapus-item-btn absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
        <div class="flex items-center gap-2 mb-3 sm:mb-4 pb-2 border-b border-[#E2E8F0]">
            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#2c5e4e] flex items-center justify-center">
                <span class="text-white text-xs font-bold">${newNumber}</span>
            </div>
            <span class="text-xs sm:text-sm font-medium text-gray-500">Area Pekerjaan</span>
        </div>
        <div class="mb-4 sm:mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Area <span class="text-red-500">*</span>
            </label>
            <input type="text" name="area[]" class="area-input w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" placeholder="Contoh: Area Lobby, Toilet Lt.2" required>
        </div>
        <div class="mb-4 sm:mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Keterangan <span class="text-red-500">*</span>
            </label>
            <textarea name="keterangan[]" required class="keterangan-input w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" rows="3" placeholder="Deskripsi pekerjaan..."></textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Foto Bukti <span class="text-red-500">*</span>
            </label>
            <div class="video-container relative rounded-xl overflow-hidden bg-gray-900 w-full md:max-w-md mx-auto">
                <div class="aspect-[4/3]">
                    <video class="camera-video w-full h-full object-cover" autoplay playsinline muted></video>
                    <canvas class="hidden"></canvas>
                    <button type="button" class="take-photo-btn absolute bottom-3 left-1/2 -translate-x-1/2 bg-white/95 hover:bg-white text-[#2c5e4e] font-semibold px-4 py-1.5 rounded-full shadow-lg transition-all text-sm">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Ambil Foto
                    </button>
                </div>
            </div>
            <div class="photo-preview-container hidden mt-3">
                <div class="bg-[#eaf4f1] rounded-xl p-3 flex items-center gap-3">
                    <img class="photo-preview w-16 h-16 rounded-xl object-cover border-2 border-white shadow" src="" alt="Preview">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">Foto berhasil diambil</p>
                        <button type="button" class="retake-photo-btn text-xs text-[#d4a373] hover:text-[#b88352] font-medium">
                            <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Ambil Ulang
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="foto[]" class="foto-input" />
        </div>
    `;
    
    wrapper.appendChild(newItem);
    
    const newVideo = newItem.querySelector('.camera-video');
    if (newVideo) initCamera(newVideo);
    
    const newTakeBtn = newItem.querySelector('.take-photo-btn');
    if (newTakeBtn) newTakeBtn.addEventListener('click', function() { takePhoto(this); });
    
    const newRetakeBtn = newItem.querySelector('.retake-photo-btn');
    if (newRetakeBtn) newRetakeBtn.addEventListener('click', function() { retakePhoto(this); });
    
    const newHapusBtn = newItem.querySelector('.hapus-item-btn');
    if (newHapusBtn) newHapusBtn.addEventListener('click', function() { hapusItem(this); });
}

function hapusItem(btn) {
    const item = btn.closest('.item');
    const video = item.querySelector('.camera-video');
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
    item.remove();
    updateItemNumbers();
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.item');
    items.forEach((item, index) => {
        const numberSpan = item.querySelector('.rounded-full span');
        if (numberSpan) numberSpan.textContent = index + 1;
    });
}

function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`tab-${tab}`).classList.remove('hidden');
    const inputBtn = document.getElementById('tab-input-btn');
    const riwayatBtn = document.getElementById('tab-riwayat-btn');
    if (tab === 'input') {
        inputBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        inputBtn.classList.remove('text-gray-600', 'bg-transparent');
        riwayatBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.add('text-gray-600', 'bg-transparent');
        setTimeout(() => {
            document.querySelectorAll('.camera-video').forEach(video => initCamera(video));
        }, 100);
    } else {
        riwayatBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.remove('text-gray-600', 'bg-transparent');
        inputBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        inputBtn.classList.add('text-gray-600', 'bg-transparent');
        document.querySelectorAll('.camera-video').forEach(video => {
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        });
    }
}

function showValidationModal() {
    const items = document.querySelectorAll('.item');
    let allValid = true;
    const validationSummary = [];
    
    items.forEach((item, index) => {
        const area = item.querySelector('.area-input')?.value.trim();
        const keterangan = item.querySelector('.keterangan-input')?.value.trim();
        const foto = item.querySelector('.foto-input')?.value;
        
        if (!area) allValid = false;
        if (!keterangan) allValid = false;
        if (!foto) allValid = false;
        
        validationSummary.push({
            area: index + 1,
            nama_area: area || '(belum diisi)',
            keterangan: keterangan || '(belum diisi)',
            hasFoto: !!foto
        });
    });
    
    if (!allValid) {
        alert('Mohon lengkapi semua data yang wajib diisi!\n\nPastikan Area, Keterangan, dan Foto sudah diisi untuk semua area.');
        return false;
    }
    
    const summaryDiv = document.getElementById('validationSummary');
    if (summaryDiv) {
        summaryDiv.innerHTML = '';
        
        validationSummary.forEach(item => {
            const areaDiv = document.createElement('div');
            areaDiv.className = 'border-b border-gray-200 pb-3 mb-3 last:border-0';
            areaDiv.innerHTML = `
                <div class="font-semibold text-[#2c5e4e] mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Area ${item.area}: ${item.nama_area}
                </div>
                <div class="text-sm text-gray-600 ml-6">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>${item.keterangan.length > 80 ? item.keterangan.substring(0, 80) + '...' : item.keterangan}</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>${item.hasFoto ? 'Foto sudah diambil' : 'Belum ada foto'}</span>
                    </div>
                </div>
            `;
            summaryDiv.appendChild(areaDiv);
        });
    }
    
    const modal = document.getElementById('validationModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('modal-open');
    }
    return true;
}

function closeModal() {
    const modal = document.getElementById('validationModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('modal-open');
    }
}

function submitForm() {
    closeModal();
    const form = document.getElementById('kinerjaForm');
    if (form) {
        const confirmBtn = document.getElementById('confirmSubmitBtn');
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = `
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

document.addEventListener('DOMContentLoaded', function() {
    @if(isset($sudahCheckIn) && $sudahCheckIn && (!isset($isIzinHariIni) || !$isIzinHariIni) && (!isset($sudahInputHariIni) || !$sudahInputHariIni))
        document.querySelectorAll('.camera-video').forEach(video => initCamera(video));
        document.querySelectorAll('.take-photo-btn').forEach(btn => {
            btn.addEventListener('click', function() { takePhoto(this); });
        });
        document.querySelectorAll('.retake-photo-btn').forEach(btn => {
            btn.addEventListener('click', function() { retakePhoto(this); });
        });
        document.querySelectorAll('.hapus-item-btn').forEach(btn => {
            btn.addEventListener('click', function() { hapusItem(this); });
        });
        const tambahBtn = document.getElementById('tambahAreaBtn');
        if (tambahBtn) tambahBtn.addEventListener('click', tambahForm);
        
        showTab('input');
    @endif
    
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
    
    // Auto hide success message
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }, 5000);
    }
});
</script>
@endsection