@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-4 md:p-8">
    <div class="container mx-auto max-w-6xl px-2 sm:px-6">

        {{-- ============================================================ --}}
        {{-- HEADER SECTION --}}
        {{-- ============================================================ --}}
        <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-[#eaf4f1] rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-0.5">Mandor</p>
                        <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Laporan Panen</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Verifikasi dan riwayat panen anggota</p>
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

        {{-- ============================================================ --}}
        {{-- CEK STATUS CHECK-IN --}}
        {{-- ============================================================ --}}
        @if(isset($isIzinHariIni) && $isIzinHariIni)
            {{-- IZIN/SAKIT --}}
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
                            Pengajuan Anda telah disetujui. Anda tidak perlu melakukan verifikasi panen pada hari ini.
                        </p>
                    </div>
                </div>
            </div>
        @elseif(isset($sudahCheckIn) && !$sudahCheckIn)
            {{-- BELUM CHECK-IN --}}
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-red-800 text-lg">⚠️ AKSES DIBATASI ⚠️</p>
                        <p class="text-sm text-red-700 mt-1">
                            <strong>Anda harus CHECK-IN terlebih dahulu</strong> sebelum dapat mengakses halaman verifikasi panen.
                        </p>
                        <p class="text-xs text-red-600 mt-2">
                            Silakan lakukan check-in melalui halaman absensi untuk melanjutkan.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Kembali ke Halaman Absensi
                        </a>
                    </div>
                </div>
            </div>
        @elseif(isset($sudahVerifikasiHariIni) && $sudahVerifikasiHariIni)
            {{-- SUDAH VERIFIKASI HARI INI --}}
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-green-800 text-lg">Verifikasi Hari Ini Sudah Dilakukan</p>
                        <p class="text-sm text-green-700 mt-1">
                            Anda sudah melakukan verifikasi laporan panen untuk hari ini. 
                            Tidak dapat melakukan verifikasi ulang.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- KONTEN UTAMA (HANYA TAMPIL JIKA SUDAH CHECK-IN DAN TIDAK IZIN) --}}
        {{-- ============================================================ --}}
        @if((isset($sudahCheckIn) && $sudahCheckIn) && (!isset($isIzinHariIni) || !$isIzinHariIni))

        {{-- CUSTOM MODAL KONFIRMASI VERIFIKASI --}}
        <div id="verifikasiModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center transition-all duration-300">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 overflow-hidden transform transition-all duration-300 scale-95">
                <div class="bg-[#2c5e4e] px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-white">Konfirmasi Verifikasi</h3>
                    </div>
                </div>
                <div class="p-5">
                    <p class="text-gray-700 text-sm mb-3 text-center">⚠️ <strong>Peringatan!</strong></p>
                    <p class="text-gray-600 text-sm text-center">Data yang sudah diverifikasi <strong class="text-red-600">tidak dapat diubah kembali</strong>.</p>
                    <p class="text-gray-500 text-xs text-center mt-2 mb-4">Pastikan semua data panen sudah benar.</p>
                    
                    <div class="bg-gray-50 rounded-lg p-3 mb-5">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Total Janjang:</span>
                            <span class="font-semibold text-gray-700" id="modalTotalJanjang">-</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Total Brondolan:</span>
                            <span class="font-semibold text-gray-700" id="modalTotalBrondolan">-</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Berat Timbangan:</span>
                            <span class="font-semibold text-[#2c5e4e]" id="modalBeratTimbangan">-</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="confirmVerifikasiSubmit()" class="flex-1 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white font-semibold py-2 rounded-lg transition-all text-sm">
                            Ya, Verifikasi
                        </button>
                        <button onclick="closeVerifikasiModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition-all text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB NAVIGATION --}}
        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button onclick="showTab('input')" id="tab-input-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap">
                Verifikasi Panen
            </button>
            <button onclick="showTab('riwayat')" id="tab-riwayat-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap">
                Riwayat Verifikasi
            </button>
        </div>

        {{-- TAB: INPUT / VERIFIKASI --}}
        <div id="tab-input" class="tab-content">
            @if($sudahVerifikasiHariIni)
                <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                    <div class="text-center py-12 md:py-16">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-800">Verifikasi Selesai</h3>
                        <p class="text-sm text-gray-500 mt-1">Anda sudah melakukan verifikasi untuk hari ini</p>
                    </div>
                </div>
            @else
                @forelse($laporanGroup as $tanggal => $items)
                    @php
                        $statusHari = $items->first()?->status ?? null;
                        $sudahVerifikasi = $statusHari === 'diverifikasi_mandor';
                        $totalJanjang = $items->sum('janjang');
                        $totalBrondolan = $items->sum('brondolan_kg');
                        $tanggalObj = \Carbon\Carbon::parse($tanggal);
                        $isToday = $tanggalObj->isToday();
                    @endphp

                    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden mb-6 transition-all hover:shadow-md">
                        {{-- HEADER CARD --}}
                        <div class="bg-gradient-to-r from-[#2c5e4e] to-[#1f4a3d] px-5 md:px-7 py-4 md:py-5">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-base md:text-lg font-bold text-white">
                                            {{ $tanggalObj->translatedFormat('l, d F Y') }}
                                            @if($isToday)
                                                <span class="ml-2 text-xs bg-white/20 text-white/80 px-2 py-0.5 rounded-full">Hari Ini</span>
                                            @endif
                                        </h2>
                                        <p class="text-xs text-white/70 mt-0.5">
                                            Total Anggota Input: {{ $items->count() }} orang
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="bg-white/20 rounded-xl px-3 py-1.5 md:px-4 md:py-2 text-center">
                                        <p class="text-xs text-white/70">Total Janjang</p>
                                        <p class="text-base md:text-lg font-bold text-white">{{ number_format($totalJanjang) }}</p>
                                    </div>
                                    <div class="bg-white/20 rounded-xl px-3 py-1.5 md:px-4 md:py-2 text-center">
                                        <p class="text-xs text-white/70">Total Brondolan</p>
                                        <p class="text-base md:text-lg font-bold text-white">{{ number_format($totalBrondolan, 1) }} Kg</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL DATA PANEN --}}
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-[#F8FAF9] border-b border-[#E2E8F0]">
                                    <tr>
                                        <th class="text-left px-4 md:px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Pekerja</th>
                                        <th class="text-right px-4 md:px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Brondolan</th>
                                        <th class="text-right px-4 md:px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Janjang</th>
                                        <th class="text-left px-4 md:px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr class="border-b border-[#E2E8F0] hover:bg-[#F8FAF9] transition">
                                        <td class="px-4 md:px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-[#eaf4f1] rounded-full flex items-center justify-center font-bold text-[#2c5e4e] text-sm">
                                                    {{ strtoupper(substr($item->pekerja->name ?? '-', 0, 1)) }}
                                                </div>
                                                <span class="font-medium text-gray-800">{{ $item->pekerja->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 md:px-6 py-3 text-right">
                                            <span class="font-semibold text-[#2c5e4e]">{{ number_format($item->brondolan_kg, 1) }}</span>
                                            <span class="text-xs text-gray-400 ml-1">Kg</span>
                                        </td>
                                        <td class="px-4 md:px-6 py-3 text-right">
                                            <span class="font-semibold text-gray-800">{{ number_format($item->janjang) }}</span>
                                            <span class="text-xs text-gray-400 ml-1">janjang</span>
                                        </td>
                                        <td class="px-4 md:px-6 py-3">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Menunggu Verifikasi
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- FORM VERIFIKASI --}}
                        <div class="px-5 md:px-7 py-5 bg-gray-50 border-t border-[#E2E8F0]">
                            <div class="mb-4 flex items-center gap-2 text-xs text-amber-600 bg-amber-50/50 rounded-lg px-3 py-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Verifikasi akan mengunci data panen. Pastikan data sudah benar.</span>
                            </div>
                            
                            <form method="POST" action="{{ route('mandor.panen.verifikasi', $tanggal) }}" class="space-y-4" id="form-{{ $tanggal }}">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">
                                            Total Janjang (Otomatis)
                                        </label>
                                        <input type="number" 
                                               name="total_tandan"
                                               value="{{ $totalJanjang }}"
                                               readonly
                                               class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">
                                            Total Berat Timbangan <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="total_berat_kg"
                                                   id="berat-{{ $tanggal }}"
                                                   required
                                                   placeholder="Masukkan total berat dari timbangan"
                                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition">
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea name="catatan" 
                                              rows="2"
                                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition"
                                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" 
                                            onclick="showVerifikasiModal('{{ $tanggal }}', {{ $totalJanjang }}, {{ $totalBrondolan }})"
                                            class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 shadow-md flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Verifikasi & Konfirmasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                        <div class="text-center py-12 md:py-16">
                            <div class="w-20 h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-base md:text-lg font-semibold text-gray-800">Belum Ada Data Panen</h3>
                            <p class="text-sm text-gray-500 mt-1">Belum ada laporan panen dari anggota yang perlu diverifikasi</p>
                        </div>
                    </div>
                @endforelse
            @endif
        </div>

        {{-- TAB: RIWAYAT VERIFIKASI --}}
        <div id="tab-riwayat" class="tab-content hidden">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-5 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base md:text-lg font-semibold text-gray-700">Riwayat Verifikasi</h2>
                            <p class="text-xs text-gray-500">Data panen yang sudah diverifikasi oleh mandor</p>
                        </div>
                    </div>
                    @php
                        $riwayatCount = collect($laporanGroup)
                            ->filter(fn($items) => optional($items->first())->status === 'diverifikasi_mandor')
                            ->count();
                    @endphp
                    <div class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1.5 md:px-4 md:py-2 rounded-xl font-semibold flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Total: {{ $riwayatCount }}
                    </div>
                </div>

                <div class="p-5 md:p-7">
                    @php
                        $riwayat = collect($laporanGroup)
                            ->filter(function($items) {
                                return optional($items->first())->status === 'diverifikasi_mandor';
                            });
                    @endphp

                    @if($riwayat->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($riwayat as $tanggal => $items)
                                @php
                                    $firstItem = $items->first();
                                    $totalJanjang = $items->sum('janjang');
                                    $totalBrondolan = $items->sum('brondolan_kg');
                                    $tanggalObj = \Carbon\Carbon::parse($tanggal);
                                @endphp
                                <div class="border border-[#E2E8F0] rounded-xl overflow-hidden hover:shadow-md transition-all">
                                    <div class="bg-gradient-to-r from-[#2c5e4e] to-[#1f4a3d] px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-white font-semibold text-sm">
                                                    {{ $tanggalObj->translatedFormat('d F Y') }}
                                                </span>
                                            </div>
                                            <span class="bg-green-500/30 text-green-200 text-xs px-2 py-0.5 rounded-full">Diverifikasi</span>
                                        </div>
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">Total Anggota</span>
                                            <span class="font-semibold text-gray-800">{{ $items->count() }} orang</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">Total Janjang</span>
                                            <span class="font-semibold text-gray-800">{{ number_format($totalJanjang) }} janjang</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">Total Brondolan</span>
                                            <span class="font-semibold text-[#2c5e4e]">{{ number_format($totalBrondolan, 1) }} Kg</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">Berat Verifikasi</span>
                                            <span class="font-semibold text-[#2c5e4e]">{{ number_format($firstItem->total_berat_kg ?? 0, 1) }} Kg</span>
                                        </div>
                                        @if($firstItem->catatan)
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <p class="text-xs text-gray-500">Catatan:</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $firstItem->catatan }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-base md:text-lg font-semibold text-gray-800">Belum Ada Riwayat Verifikasi</h3>
                            <p class="text-sm text-gray-500 mt-1">Data panen yang sudah diverifikasi akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ANGGOTA YANG BELUM INPUT PANEN --}}
        @php
            $today = \Carbon\Carbon::today('Asia/Jakarta');
            
            $semuaPekerja = \App\Models\User::where('mandor_id', Auth::id())
                ->where('role', 'user')
                ->orderBy('name')
                ->get();
            
            $sudahInputIds = \App\Models\LaporanPanen::where('mandor_id', Auth::id())
                ->whereDate('tanggal', $today)
                ->pluck('pekerja_id')
                ->toArray();
            
            $belumInputList = [];
            foreach($semuaPekerja as $pekerja) {
                if(!in_array($pekerja->id, $sudahInputIds)) {
                    $belumInputList[] = $pekerja;
                }
            }
        @endphp

        @if(count($belumInputList) > 0)
        <div class="mt-6 mb-4 bg-white rounded-xl shadow-sm border border-amber-100 overflow-hidden">
            <div class="bg-amber-50/50 px-5 md:px-7 py-3 md:py-4 border-b border-amber-100">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-medium text-amber-600">Belum Input Panen Hari Ini</span>
                    <span class="text-xs text-gray-400 ml-1">{{ $today->translatedFormat('d F Y') }}</span>
                </div>
            </div>
            <div class="p-4 md:p-5">
                <div class="flex flex-wrap gap-2">
                    @foreach($belumInputList as $pekerja)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-50/50 rounded-full text-sm border border-amber-100">
                        <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center font-medium text-amber-600 text-xs">
                            {{ strtoupper(substr($pekerja->name, 0, 1)) }}
                        </div>
                        <span class="text-gray-600 text-sm">{{ $pekerja->name }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ count($belumInputList) }} anggota belum menginput panen hari ini.</span>
                </div>
            </div>
        </div>
        @endif

        {{-- INFO PANDUAN --}}
        <div class="mt-5 sm:mt-6 bg-[#eaf4f1]/50 rounded-xl p-3 sm:p-4 border border-[#2c5e4e]/10">
            <div class="flex items-start gap-2 sm:gap-3">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2c5e4e] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-[#2c5e4e]">Panduan Verifikasi</p>
                    <ul class="text-xs sm:text-sm text-gray-600 mt-1 space-y-0.5">
                        <li>• Verifikasi dilakukan setelah menimbang hasil panen</li>
                        <li>• Data yang sudah diverifikasi tidak dapat diubah kembali</li>
                        <li>• Pastikan total berat timbangan sesuai dengan hasil penimbangan</li>
                        <li>• Anda dapat melihat riwayat verifikasi pada tab Riwayat</li>
                    </ul>
                </div>
            </div>
        </div>

        @endif {{-- TUTUP KONDISI SUDAH CHECK-IN --}}

    </div>
</div>

<style>
.tab-content {
    transition: all 0.3s ease;
}
</style>

<script>
// Variables untuk modal
let currentFormToSubmit = null;
let currentTotalJanjang = 0;
let currentTotalBrondolan = 0;
let currentBeratTimbangan = 0;

function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`tab-${tab}`).classList.remove('hidden');

    const inputBtn = document.getElementById('tab-input-btn');
    const riwayatBtn = document.getElementById('tab-riwayat-btn');

    if (tab === 'input') {
        inputBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        inputBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        riwayatBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
    } else {
        riwayatBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        inputBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        inputBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
    }
}

// Fungsi untuk menampilkan modal konfirmasi
function showVerifikasiModal(tanggal, totalJanjang, totalBrondolan) {
    const beratInput = document.getElementById(`berat-${tanggal}`);
    
    if (!beratInput.value || beratInput.value <= 0) {
        alert('Harap masukkan total berat timbangan terlebih dahulu!');
        beratInput.focus();
        return;
    }
    
    currentFormToSubmit = document.getElementById(`form-${tanggal}`);
    currentTotalJanjang = totalJanjang;
    currentTotalBrondolan = totalBrondolan;
    currentBeratTimbangan = beratInput.value;
    
    document.getElementById('modalTotalJanjang').textContent = totalJanjang.toLocaleString();
    document.getElementById('modalTotalBrondolan').textContent = totalBrondolan.toLocaleString();
    document.getElementById('modalBeratTimbangan').textContent = beratInput.value;
    
    const modal = document.getElementById('verifikasiModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal
function closeVerifikasiModal() {
    const modal = document.getElementById('verifikasiModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 200);
    currentFormToSubmit = null;
}

// Fungsi untuk submit form setelah konfirmasi
function confirmVerifikasiSubmit() {
    if (currentFormToSubmit) {
        currentFormToSubmit.submit();
    }
    closeVerifikasiModal();
}

// Tutup modal jika klik di luar
document.getElementById('verifikasiModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVerifikasiModal();
    }
});

// Set default tab
document.addEventListener('DOMContentLoaded', function() {
    showTab('input');
});
</script>
@endsection