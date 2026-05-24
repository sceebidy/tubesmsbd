@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        
        {{-- ============================================================ --}}
        {{-- HEADER SECTION --}}
        {{-- ============================================================ --}}
        <div class="mb-8 pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Riwayat Absensi</h1>
                        <p class="text-sm text-gray-500 mt-1">Rekap kehadiran {{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SUMMARY STATS --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
            
            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Absen</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->total() }}
                            <span class="text-base font-medium text-gray-400">kali</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Hadir</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->where('status','hadir')->count() }}
                            <span class="text-base font-medium text-gray-400">hari</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Terlambat</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->where('status','terlambat')->count() }}
                            <span class="text-base font-medium text-gray-400">hari</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#fef5ed] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Izin/Sakit</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->whereIn('status', ['izin', 'sakit'])->count() }}
                            <span class="text-base font-medium text-gray-400">hari</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Selesai</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->whereNotNull('check_out')->count() }}
                            <span class="text-base font-medium text-gray-400">kali</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- FILTER SECTION --}}
        {{-- ============================================================ --}}
        <div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] mb-6 shadow-sm">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter Status
                    </label>
                    <div class="relative">
                        <select id="filterStatus" onchange="filterTable()" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition appearance-none bg-white text-gray-700">
                            <option value="all">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Filter Bulan
                    </label>
                    <div class="relative">
                        <input type="month" id="filterMonth" onchange="filterTable()" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition text-gray-700">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Cari Tanggal
                    </label>
                    <div class="relative">
                        <input type="date" id="filterDate" onchange="filterTable()" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition text-gray-700">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <button onclick="resetFilters()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all border border-[#E2E8F0]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                </div>

                <div>
                    <button onclick="exportToCSV()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white rounded-xl text-sm font-medium transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- INFO BADGE UNTUK TANGGAL MENDATANG --}}
        {{-- ============================================================ --}}
        <div class="mb-4 flex justify-end">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>📅 Izin/Sakit yang sudah disetujui untuk tanggal mendatang akan muncul di sini</span>
            </span>
        </div>

        {{-- ============================================================ --}}
        {{-- TABLE CARD --}}
        {{-- ============================================================ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
            <div class="px-7 py-5 bg-white border-b border-[#eaf4f1] flex items-center gap-3">
                <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h2 class="text-lg font-semibold text-gray-700">Daftar Absensi</h2>
            </div>
            
            <div class="p-7">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="attendanceTable">
                        <thead class="bg-gray-50 border-b border-[#eaf4f1]">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Tanggal</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Hari</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Masuk</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Pulang</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Durasi</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Status</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($riwayat as $absen)
                            @php
                                $tanggalAbsen = \Carbon\Carbon::parse($absen->date);
                                $isFutureDate = $tanggalAbsen->isFuture();
                                $isToday = $tanggalAbsen->isToday();
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition {{ $isFutureDate ? 'bg-blue-50/30' : '' }}" 
                                data-status="{{ $absen->status }}" 
                                data-date="{{ $tanggalAbsen->format('Y-m-d') }}" 
                                data-month="{{ $tanggalAbsen->format('Y-m') }}">
                                <td class="px-5 py-4 font-semibold text-gray-900" data-label="Tanggal">
                                    {{ $tanggalAbsen->format('d/m/Y') }}
                                    @if($isFutureDate)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-600">
                                            Akan Datang
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-700" data-label="Hari">
                                    {{ $tanggalAbsen->translatedFormat('l') }}
                                </td>
                                <td class="px-5 py-4 font-semibold" data-label="Masuk">
                                    @if($absen->check_in)
                                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($absen->check_in)->format('H:i') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-semibold" data-label="Pulang">
                                    @if($absen->check_out)
                                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($absen->check_out)->format('H:i') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4" data-label="Durasi">
                                    @if($absen->check_in && $absen->check_out)
                                        @php
                                            $checkIn = \Carbon\Carbon::parse($absen->check_in);
                                            $checkOut = \Carbon\Carbon::parse($absen->check_out);
                                            $duration = $checkIn->diff($checkOut);
                                        @endphp
                                        <span class="text-sm text-gray-600">{{ $duration->h }} jam {{ $duration->i }} menit</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4" data-label="Status">
                                    @if($absen->status == 'hadir')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf4f1] text-[#2c5e4e]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Hadir
                                        </span>
                                    @elseif($absen->status == 'terlambat')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#fef5ed] text-[#d4a373]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Terlambat
                                        </span>
                                    @elseif($absen->status == 'izin')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            Izin
                                            @if($isFutureDate)
                                                <span class="ml-1 text-[10px] bg-blue-200 px-1 rounded">disetujui</span>
                                            @endif
                                        </span>
                                    @elseif($absen->status == 'sakit')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Sakit
                                            @if($isFutureDate)
                                                <span class="ml-1 text-[10px] bg-purple-200 px-1 rounded">disetujui</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">{{ $absen->status }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-500" data-label="Keterangan">
                                    @if($isFutureDate && in_array($absen->status, ['izin', 'sakit']))
                                        <span class="text-blue-600 text-xs">✓ Sudah disetujui untuk tanggal ini</span>
                                    @else
                                        {{ $absen->note ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center">
                                    <div class="text-center py-8">
                                        <div class="text-6xl mb-3">📝</div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Data Absensi</h3>
                                        <p class="text-gray-500 mb-4">Silakan lakukan absensi terlebih dahulu untuk melihat riwayat</p>
                                        <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                            </svg>
                                            Absen Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($riwayat->hasPages())
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} data
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @if($riwayat->onFirstPage())
                                <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">← Sebelumnya</span>
                            @else
                                <a href="{{ $riwayat->previousPageUrl() }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">← Sebelumnya</a>
                            @endif

                            @foreach($riwayat->getUrlRange(1, $riwayat->lastPage()) as $page => $url)
                                @if($page == $riwayat->currentPage())
                                    <span class="px-3 py-2 bg-[#2c5e4e] text-white rounded-lg text-sm font-semibold">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($riwayat->hasMorePages())
                                <a href="{{ $riwayat->nextPageUrl() }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">Selanjutnya →</a>
                            @else
                                <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">Selanjutnya →</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    

    </div>
</div>

<style>
    /* Custom date input styling */
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="month"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    /* Responsive table for mobile */
    @media (max-width: 768px) {
        #attendanceTable thead {
            display: none;
        }
        
        #attendanceTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 1rem;
            background: white;
        }
        
        #attendanceTable tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border: none;
        }
        
        #attendanceTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6B7280;
            margin-right: 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    }
</style>

<script>
// Filter Function
function filterTable() {
    const status = document.getElementById('filterStatus').value;
    const month = document.getElementById('filterMonth').value;
    const date = document.getElementById('filterDate').value;
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        if (row.querySelector('td') && row.querySelector('td').getAttribute('colspan') !== '7') {
            const rowStatus = row.getAttribute('data-status');
            const rowDate = row.getAttribute('data-date');
            const rowMonth = row.getAttribute('data-month');
            
            let show = true;
            
            if (status !== 'all' && rowStatus !== status) {
                show = false;
            }
            
            if (month && rowMonth !== month) {
                show = false;
            }
            
            if (date && rowDate !== date) {
                show = false;
            }
            
            row.style.display = show ? '' : 'none';
        }
    });
    
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none' && row.querySelector('td') && row.querySelector('td').getAttribute('colspan') !== '7');
    let emptyMessage = document.getElementById('emptyFilterMessage');
    
    if (visibleRows.length === 0 && rows.length > 0 && rows[0].querySelector('td') && rows[0].querySelector('td').getAttribute('colspan') !== '7') {
        if (!emptyMessage) {
            const tbody = document.getElementById('tableBody');
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyFilterMessage';
            emptyRow.innerHTML = '<td colspan="7"><div class="text-center py-12"><div class="text-6xl mb-3">🔍</div><h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data</h3><p class="text-gray-500">Tidak ditemukan data absensi dengan filter yang dipilih</p></div></td>';
            tbody.appendChild(emptyRow);
        }
    } else if (emptyMessage) {
        emptyMessage.remove();
    }
}

// Reset Filters
function resetFilters() {
    document.getElementById('filterStatus').value = 'all';
    document.getElementById('filterMonth').value = '';
    document.getElementById('filterDate').value = '';
    filterTable();
}

// Export to CSV
function exportToCSV() {
    const rows = document.querySelectorAll('#tableBody tr');
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none' && row.querySelector('td') && row.querySelector('td').getAttribute('colspan') !== '7');
    
    if (visibleRows.length === 0) {
        alert('Tidak ada data untuk diexport');
        return;
    }
    
    let csvContent = "Tanggal,Hari,Masuk,Pulang,Durasi,Status,Keterangan\n";
    
    visibleRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const rowData = [];
            cells.forEach(cell => {
                let text = cell.innerText.trim();
                if (text.includes(',')) {
                    text = `"${text}"`;
                }
                rowData.push(text);
            });
            csvContent += rowData.join(',') + "\n";
        }
    });
    
    const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'riwayat_absensi_' + new Date().toISOString().slice(0,10) + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection