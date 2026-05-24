@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-4 md:p-8">
    <div class="container mx-auto max-w-6xl px-2 sm:px-6">

        {{-- HEADER --}}
        <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-[#eaf4f1] rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-0.5">Security</p>
                        <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Sistem Patroli</h1>
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
                    <p class="font-semibold text-red-800 text-lg">Belum Melakukan Check-in!</p>
                    <p class="text-sm text-red-700 mt-1">
                        Anda harus melakukan CHECK-IN terlebih dahulu sebelum dapat menginput laporan patroli.
                        Silakan lakukan check-in melalui halaman absensi.
                    </p>
                </div>
                <div>
                    <a href="{{ route('attendance.index') }}" 
                       class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Kembali ke Halaman Absensi
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- VALIDASI IZIN/SAKIT HARI INI (hanya tampil jika sudah check-in) --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && isset($isIzinHariIni) && $isIzinHariIni)
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
                        Pengajuan Anda telah disetujui. Anda tidak perlu menginput laporan patroli pada hari ini.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- PERINGATAN JIKA SUDAH INPUT PATROLI HARI INI (hanya tampil jika sudah check-in) --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && isset($sudahInputHariIni) && $sudahInputHariIni && (!isset($isIzinHariIni) || !$isIzinHariIni))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-green-800 text-lg">Patroli Hari Ini Sudah Diinput</p>
                    <p class="text-sm text-green-700 mt-1">
                        Anda sudah menginput laporan patroli untuk hari ini. 
                        Tidak dapat menginput ulang.
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

        {{-- TAB NAVIGATION (HANYA TAMPIL JIKA SUDAH CHECK-IN, BELUM INPUT & TIDAK IZIN) --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && (!isset($isIzinHariIni) || !$isIzinHariIni) && (!isset($sudahInputHariIni) || !$sudahInputHariIni))
        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button type="button" onclick="showTab('input')" id="tab-input-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap">
                Input Patroli
            </button>
            <button type="button" onclick="showTab('riwayat')" id="tab-riwayat-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap">
                Riwayat Hari Ini
            </button>
        </div>
        @endif

        {{-- TAB: INPUT PATROLI (HANYA TAMPIL JIKA SUDAH CHECK-IN, BELUM INPUT & TIDAK IZIN) --}}
        @if(isset($sudahCheckIn) && $sudahCheckIn && (!isset($isIzinHariIni) || !$isIzinHariIni) && (!isset($sudahInputHariIni) || !$sudahInputHariIni))
        <div id="tab-input" class="tab-content">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <!-- Form input patroli (konten sama seperti sebelumnya) -->
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-base md:text-lg font-semibold text-gray-700">Form Input Patroli</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">* wajib diisi</span>
                    </div>
                </div>
                <div class="p-4 md:p-7">
                    <form action="{{ route('security.patroli.store') }}" method="POST" id="patroliForm" enctype="multipart/form-data">
                        @csrf
                        <div id="wrapper" class="space-y-5 sm:space-y-6">
                            {{-- ITEM PERTAMA --}}
                            <div class="item bg-[#F8FAF9] rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] relative">
                                <div class="flex items-center gap-2 mb-3 sm:mb-4 pb-2 border-b border-[#E2E8F0]">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#2c5e4e] flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">1</span>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-500">Area Patroli</span>
                                </div>

                                <div class="mb-4 sm:mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Nama Area <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="lokasi[]" class="lokasi-input w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" placeholder="Contoh: Gudang Belakang, Area Perkebunan Timur" required>
                                </div>

                                <div class="mb-4 sm:mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Keterangan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="keterangan[]" required class="keterangan-input w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" rows="3" placeholder="Kondisi area patroli..."></textarea>
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
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                <button type="button" class="retake-photo-btn text-xs text-[#d4a373] hover:text-[#b88352] font-medium flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
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
                                Tambah Area Patroli
                            </button>
                            <button type="submit" id="submitBtn" class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2 rounded-xl font-semibold shadow-md transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Simpan Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- TAMPILKAN RIWAYAT (HANYA TAMPIL JIKA SUDAH CHECK-IN) --}}
        @php
            $showRiwayatOnly = (isset($sudahCheckIn) && $sudahCheckIn) && ((isset($isIzinHariIni) && $isIzinHariIni) || (isset($sudahInputHariIni) && $sudahInputHariIni));
        @endphp

        @if(isset($sudahCheckIn) && $sudahCheckIn)
        <div id="tab-riwayat" class="@if(!$showRiwayatOnly) tab-content hidden @endif">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-base md:text-lg font-semibold text-gray-700">Riwayat Patroli Hari Ini</h2>
                    </div>
                    <div class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1.5 md:px-4 md:py-2 rounded-xl font-semibold text-sm md:text-base">
                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Total: {{ $riwayatHariIni->count() }}
                    </div>
                </div>
                <div class="p-4 md:p-7">
                    @if($riwayatHariIni->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            @foreach($riwayatHariIni as $item)
                                <div class="border border-[#E2E8F0] rounded-xl overflow-hidden bg-white transition-all hover:shadow-md">
                                    <img src="{{ asset('storage/' . $item->foto) }}"
                                         class="w-full h-48 md:h-56 object-cover"
                                         onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ada'">
                                    <div class="p-4 md:p-5">
                                        <div class="flex justify-between items-start mb-3 flex-wrap gap-2">
                                            <div>
                                                <h3 class="text-base md:text-lg font-bold text-gray-800 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $item->nama_area ?? $item->lokasi }}
                                                </h3>
                                                <p class="text-xs md:text-sm text-gray-500 mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y - H:i') }}
                                                </p>
                                            </div>
                                            <span class="bg-[#eaf4f1] text-[#2c5e4e] px-2 py-0.5 md:px-3 md:py-1 rounded-full text-xs md:text-sm font-semibold">
                                                Patroli
                                            </span>
                                        </div>
                                        <div class="text-gray-700 mt-3 pt-3 border-t border-[#E2E8F0]">
                                            <p class="text-xs md:text-sm">
                                                <span class="font-semibold">Keterangan:</span><br>
                                                {{ $item->keterangan ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 md:py-12">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-base md:text-lg font-semibold text-gray-800">Belum Ada Patroli Hari Ini</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">Silakan input patroli terlebih dahulu</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- TOMBOL KEMBALI KE ABSENSI (JIKA SUDAH INPUT ATAU IZIN ATAU BELUM CHECK-IN) --}}
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

{{-- Modal Konfirmasi Validasi --}}
<div id="validationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <!-- konten modal sama seperti sebelumnya -->
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden shadow-2xl">
        <div class="bg-[#2c5e4e] px-6 py-4">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Konfirmasi Data Patroli
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Pastikan data berikut sudah benar:</p>
            <div id="validationSummary" class="space-y-3 max-h-96 overflow-y-auto"></div>
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

<script>
// Store video streams
let videoStreams = new Map();

// Fungsi inisialisasi kamera
async function initCamera(videoElement) {
    if (!videoElement) return;
    
    // Stop stream yang sudah berjalan
    if (videoElement.srcObject) {
        videoElement.srcObject.getTracks().forEach(track => track.stop());
        videoElement.srcObject = null;
    }
    
    try {
        // Coba dengan facingMode environment (kamera belakang)
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'environment' }, 
            audio: false 
        });
        videoElement.srcObject = stream;
        videoStreams.set(videoElement, stream);
        
        await new Promise((resolve) => {
            videoElement.onloadedmetadata = () => {
                videoElement.play().catch(e => console.log('Play error:', e));
                resolve();
            };
        });
        console.log('Camera initialized successfully');
    } catch (err) {
        console.error('Camera error with environment:', err);
        
        // Coba dengan facingMode user (kamera depan) jika gagal
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: true, 
                audio: false 
            });
            videoElement.srcObject = stream;
            videoStreams.set(videoElement, stream);
            
            await new Promise((resolve) => {
                videoElement.onloadedmetadata = () => {
                    videoElement.play().catch(e => console.log('Play error:', e));
                    resolve();
                };
            });
            console.log('Camera initialized with default settings');
        } catch (err2) {
            console.error('All camera attempts failed:', err2);
            // Tampilkan pesan error di element
            const videoContainer = videoElement.closest('.video-container');
            if (videoContainer) {
                videoContainer.innerHTML = `
                    <div class="aspect-[4/3] bg-gray-800 rounded-xl flex flex-col items-center justify-center text-white p-4">
                        <svg class="w-12 h-12 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm font-medium">Kamera tidak tersedia</p>
                        <p class="text-xs text-gray-400 mt-1">Pastikan izin kamera diberikan</p>
                        <button type="button" onclick="location.reload()" class="mt-3 px-3 py-1 bg-[#2c5e4e] rounded-lg text-xs">Refresh Halaman</button>
                    </div>
                `;
            }
        }
    }
}

// Fungsi stop kamera
function stopCamera(videoElement) {
    if (videoElement && videoElement.srcObject) {
        videoElement.srcObject.getTracks().forEach(track => track.stop());
        videoElement.srcObject = null;
    }
}

// Fungsi ambil foto
function takePhoto(btn) {
    const item = btn.closest('.item');
    const video = item.querySelector('.camera-video');
    const canvas = item.querySelector('canvas');
    const fotoInput = item.querySelector('.foto-input');
    const previewContainer = item.querySelector('.photo-preview-container');
    const previewImg = item.querySelector('.photo-preview');
    const videoContainer = item.querySelector('.video-container');
    
    if (!video || !video.videoWidth || video.videoWidth === 0) {
        alert('Kamera belum siap. Tunggu sebentar atau refresh halaman.');
        return;
    }
    
    // Set canvas size sesuai video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Gambar video ke canvas
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Konversi ke base64
    const dataURL = canvas.toDataURL('image/jpeg', 0.85);
    
    // Set value ke input hidden
    fotoInput.value = dataURL;
    
    // Tampilkan preview
    previewImg.src = dataURL;
    previewContainer.classList.remove('hidden');
    videoContainer.classList.add('hidden');
    
    // Stop kamera untuk hemat baterai
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
        video.srcObject = null;
    }
    
    // Tandai bahwa foto sudah diambil
    btn.textContent = '✓ Foto Diambil';
    btn.classList.add('bg-green-600', 'text-white');
    btn.classList.remove('bg-white/95', 'text-[#2c5e4e]');
}

// Fungsi retake/ambil ulang foto
function retakePhoto(btn) {
    const item = btn.closest('.item');
    const video = item.querySelector('.camera-video');
    const fotoInput = item.querySelector('.foto-input');
    const previewContainer = item.querySelector('.photo-preview-container');
    const videoContainer = item.querySelector('.video-container');
    const takeBtn = item.querySelector('.take-photo-btn');
    
    // Reset nilai
    fotoInput.value = '';
    previewContainer.classList.add('hidden');
    videoContainer.classList.remove('hidden');
    
    // Reset tombol
    if (takeBtn) {
        takeBtn.textContent = 'Ambil Foto';
        takeBtn.classList.remove('bg-green-600', 'text-white');
        takeBtn.classList.add('bg-white/95', 'text-[#2c5e4e]');
    }
    
    // Inisialisasi ulang kamera
    if (video) {
        initCamera(video);
    }
}

// Fungsi tambah form area
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
            <span class="text-xs sm:text-sm font-medium text-gray-500">Area Patroli</span>
        </div>
        <div class="mb-4 sm:mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Nama Area <span class="text-red-500">*</span>
            </label>
            <input type="text" name="lokasi[]" class="lokasi-input w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" placeholder="Contoh: Area Lobby" required>
        </div>
        <div class="mb-4 sm:mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Keterangan <span class="text-red-500">*</span>
            </label>
            <textarea name="keterangan[]" required class="keterangan-input w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition" rows="3" placeholder="Kondisi area patroli..."></textarea>
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
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <button type="button" class="retake-photo-btn text-xs text-[#d4a373] hover:text-[#b88352] font-medium flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
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
    
    // Inisialisasi event listener untuk item baru
    const newVideo = newItem.querySelector('.camera-video');
    const newTakeBtn = newItem.querySelector('.take-photo-btn');
    const newRetakeBtn = newItem.querySelector('.retake-photo-btn');
    const newHapusBtn = newItem.querySelector('.hapus-item-btn');
    
    if (newVideo) initCamera(newVideo);
    if (newTakeBtn) newTakeBtn.addEventListener('click', () => takePhoto(newTakeBtn));
    if (newRetakeBtn) newRetakeBtn.addEventListener('click', () => retakePhoto(newRetakeBtn));
    if (newHapusBtn) newHapusBtn.addEventListener('click', function() {
        const itemToRemove = this.closest('.item');
        const videoToStop = itemToRemove.querySelector('.camera-video');
        if (videoToStop && videoToStop.srcObject) {
            videoToStop.srcObject.getTracks().forEach(track => track.stop());
        }
        itemToRemove.remove();
        updateItemNumbers();
    });
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.item');
    items.forEach((item, index) => {
        const numberSpan = item.querySelector('.rounded-full span');
        if (numberSpan) numberSpan.textContent = index + 1;
    });
}

function showTab(tab) {
    const inputTab = document.getElementById('tab-input');
    const riwayatTab = document.getElementById('tab-riwayat');
    const inputBtn = document.getElementById('tab-input-btn');
    const riwayatBtn = document.getElementById('tab-riwayat-btn');
    
    if (tab === 'input') {
        if (inputTab) inputTab.classList.remove('hidden');
        if (riwayatTab) riwayatTab.classList.add('hidden');
        if (inputBtn) {
            inputBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            inputBtn.classList.remove('text-gray-600', 'hover:bg-[#eaf4f1]', 'hover:text-[#2c5e4e]');
        }
        if (riwayatBtn) {
            riwayatBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            riwayatBtn.classList.add('text-gray-600', 'hover:bg-[#eaf4f1]', 'hover:text-[#2c5e4e]');
        }
        // Inisialisasi kamera saat pindah ke tab input
        setTimeout(() => {
            document.querySelectorAll('.camera-video').forEach(video => {
                if (video && !video.srcObject) {
                    initCamera(video);
                }
            });
        }, 100);
    } else {
        if (inputTab) inputTab.classList.add('hidden');
        if (riwayatTab) riwayatTab.classList.remove('hidden');
        if (riwayatBtn) {
            riwayatBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            riwayatBtn.classList.remove('text-gray-600', 'hover:bg-[#eaf4f1]', 'hover:text-[#2c5e4e]');
        }
        if (inputBtn) {
            inputBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
            inputBtn.classList.add('text-gray-600', 'hover:bg-[#eaf4f1]', 'hover:text-[#2c5e4e]');
        }
        // Stop kamera saat pindah dari tab input
        document.querySelectorAll('.camera-video').forEach(video => {
            if (video && video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        });
    }
}

// Fungsi validasi semua item sebelum submit
function validateAllItems() {
    const items = document.querySelectorAll('.item');
    let allValid = true;
    const validationSummary = [];
    
    items.forEach((item, index) => {
        const lokasi = item.querySelector('.lokasi-input')?.value.trim();
        const keterangan = item.querySelector('.keterangan-input')?.value.trim();
        const foto = item.querySelector('.foto-input')?.value;
        
        if (!lokasi) allValid = false;
        if (!keterangan) allValid = false;
        if (!foto) allValid = false;
        
        validationSummary.push({
            area: index + 1,
            lokasi: lokasi || '(belum diisi)',
            keterangan: keterangan || '(belum diisi)',
            hasFoto: !!foto
        });
    });
    
    return { allValid, validationSummary };
}

// Tampilkan modal konfirmasi
function showValidationModal() {
    const { allValid, validationSummary } = validateAllItems();
    
    if (!allValid) {
        alert('Mohon lengkapi semua data yang wajib diisi!\n\nPastikan Nama Area, Keterangan, dan Foto sudah diisi untuk semua area patroli.');
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
                    Area ${item.area}: ${item.lokasi}
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
        modal.classList.add('flex');
        modal.classList.remove('hidden');
    }
    return true;
}

// Event listener saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi kamera untuk item pertama (hanya jika ada form input)
    @if(isset($sudahCheckIn) && $sudahCheckIn && (!isset($isIzinHariIni) || !$isIzinHariIni) && (!isset($sudahInputHariIni) || !$sudahInputHariIni))
        const firstVideo = document.querySelector('.camera-video');
        if (firstVideo) {
            setTimeout(() => initCamera(firstVideo), 500);
        }
        
        // Event listener untuk tombol ambil foto
        document.querySelectorAll('.take-photo-btn').forEach(btn => {
            btn.addEventListener('click', () => takePhoto(btn));
        });
        
        // Event listener untuk tombol retake foto
        document.querySelectorAll('.retake-photo-btn').forEach(btn => {
            btn.addEventListener('click', () => retakePhoto(btn));
        });
        
        // Event listener untuk tombol tambah area
        const tambahBtn = document.getElementById('tambahAreaBtn');
        if (tambahBtn) tambahBtn.addEventListener('click', tambahForm);
        
        // Event listener untuk tombol hapus (item pertama)
        const hapusBtns = document.querySelectorAll('.hapus-item-btn');
        hapusBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const itemToRemove = this.closest('.item');
                const videoToStop = itemToRemove.querySelector('.camera-video');
                if (videoToStop && videoToStop.srcObject) {
                    videoToStop.srcObject.getTracks().forEach(track => track.stop());
                }
                itemToRemove.remove();
                updateItemNumbers();
            });
        });
    @endif
    
    // Handler untuk submit dengan modal konfirmasi
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
        closeModalBtn.addEventListener('click', function() {
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }
    
    if (confirmSubmitBtn) {
        confirmSubmitBtn.addEventListener('click', function() {
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            
            const form = document.getElementById('patroliForm');
            if (form) {
                confirmSubmitBtn.disabled = true;
                confirmSubmitBtn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                `;
                form.submit();
            }
        });
    }
    
    // Tutup modal jika klik di luar
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }
});

// Hentikan semua kamera saat halaman ditutup atau di-unload
window.addEventListener('beforeunload', function() {
    document.querySelectorAll('.camera-video').forEach(video => {
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
    });
});
</script>>
@endsection