@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 md:p-8">
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-wide mb-1">Admin</p>
                <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Admin Dashboard</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Dashboard Monitoring Sistem Perusahaan Sawit</p>
            </div>
            <span class="inline-block px-3 py-1 md:px-4 md:py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-xs md:text-sm font-medium self-start sm:self-center">
                PT. Sipirok Indah
            </span>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 mb-6 md:mb-8">
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Total Pegawai</p>
                    <p class="text-xl md:text-3xl font-bold text-gray-800">{{ number_format($totalPegawai ?? 0) }}</p>
                    <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Seluruh Tim</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Hadir Hari Ini</p>
                    <p class="text-xl md:text-3xl font-bold text-[#2c5e4e]">{{ number_format($hadirHariIni ?? 0) }}</p>
                    <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Total Kehadiran</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Produksi Hari Ini</p>
                    <p class="text-xl md:text-3xl font-bold text-[#2c5e4e]">{{ number_format($produksiHariIni ?? 0, 1) }} <span class="text-xs md:text-sm font-medium text-gray-400">kg</span></p>
                    <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Total Panen</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#2c5e4e] rounded-xl md:rounded-2xl p-3 md:p-5 transition-all hover:bg-[#1f4a3d] hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-white/70 mb-1 md:mb-2">Jumlah Alpha</p>
                    <p class="text-xl md:text-3xl font-bold text-white">{{ number_format($totalAlpha ?? 0) }}</p>
                    <p class="text-[9px] md:text-xs text-white/60 mt-0.5 md:mt-1">Tidak Hadir</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 1: Statistik Kehadiran & Trend --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        {{-- Donut Chart Kehadiran --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Status Absensi Hari Ini</h3>
            <p class="text-xs text-gray-400 mb-4">Distribusi kehadiran tim</p>
            <div class="h-[180px] md:h-[200px] relative">
                <canvas id="attendanceChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <div class="flex justify-between items-center text-xs p-2 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2c5e4e]"></span>
                        <span class="text-gray-600">Hadir</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $hadirHariIni ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center text-xs p-2 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#d4a373]"></span>
                        <span class="text-gray-600">Terlambat</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $totalTerlambat ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center text-xs p-2 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-gray-600">Izin</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $izinHariIni ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center text-xs p-2 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                        <span class="text-gray-600">Sakit</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $sakitHariIni ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center text-xs p-2 rounded-lg bg-gray-50 col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span class="text-gray-600">Alpha</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $totalAlpha ?? 0 }}</span>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3 text-center">*Alpha = Pegawai yang belum melakukan absensi hari ini</p>
        </div>

        {{-- Ringkasan & Trend Kehadiran --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Ringkasan & Trend</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 md:p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <span class="text-sm font-medium text-gray-600">Kehadiran</span>
                    <div class="text-right">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#eaf4f1] text-[#2c5e4e]">
                            {{ $rateKehadiran ?? 0 }}%
                        </span>
                        @if(isset($trendKehadiran) && $trendKehadiran != 0)
                            <span class="text-xs ml-2 {{ $trendKehadiran > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trendKehadiran > 0 ? '↑' : '↓' }} {{ abs($trendKehadiran) }}%
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 md:p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <span class="text-sm font-medium text-gray-600">Produksi Panen</span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#eaf4f1] text-[#2c5e4e]">
                        {{ number_format($produksiHariIni ?? 0, 1) }} kg
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 md:p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <span class="text-sm font-medium text-gray-600">Total Tandan</span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#eaf4f1] text-[#2c5e4e]">
                        {{ number_format($totalTandanHariIni ?? 0) }} Tandan
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 md:p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <span class="text-sm font-medium text-gray-600">Izin & Sakit</span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                        {{ ($izinHariIni ?? 0) + ($sakitHariIni ?? 0) }} Orang
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Grafik Kehadiran 7 Hari --}}
    <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm mb-6 md:mb-8">
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Grafik Kehadiran 7 Hari Terakhir</h3>
        <p class="text-xs text-gray-400 mb-4">Perbandingan kehadiran, izin, sakit, dan alpha</p>
        <div class="h-[250px] md:h-[300px]">
            <canvas id="lineAttendanceChart"></canvas>
        </div>
    </div>

    {{-- Row 3: Grafik Kinerja Cleaning & Patroli --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        {{-- Grafik Cleaning --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Kinerja Cleaning Service</h3>
                    <p class="text-xs text-gray-400">Grafik laporan cleaning 7 hari terakhir</p>
                </div>
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
            <div class="h-[200px] md:h-[250px]">
                <canvas id="cleaningChart"></canvas>
            </div>
            <div class="mt-4 text-xs text-gray-500 text-center">
                Total Laporan: {{ collect($cleaningChart ?? [])->sum('total_laporan') }} laporan
            </div>
        </div>

        {{-- Grafik Patroli --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Kinerja Patroli Security</h3>
                    <p class="text-xs text-gray-400">Grafik patroli security 7 hari terakhir</p>
                </div>
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
            <div class="h-[200px] md:h-[250px]">
                <canvas id="patroliChart"></canvas>
            </div>
            <div class="mt-4 text-xs text-gray-500 text-center">
                Total Patroli: {{ collect($patroliChart ?? [])->sum('total_patroli') }} laporan
            </div>
        </div>
    </div>

    {{-- Row 4: Grafik Produksi Panen & Overview Departemen --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        {{-- Grafik Produksi Panen --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Produksi Panen</h3>
                    <p class="text-xs text-gray-400">Grafik hasil panen 7 hari terakhir</p>
                </div>
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="h-[200px] md:h-[250px]">
                <canvas id="panenChart"></canvas>
            </div>
            <div class="mt-4 text-xs text-gray-500 text-center">
                Total Produksi: {{ number_format(collect($panenChart ?? [])->sum('total_berat'), 1) }} kg | Total Tandan: {{ number_format(collect($panenChart ?? [])->sum('total_tandan')) }}
            </div>
        </div>

        {{-- Overview Departemen --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Overview Departemen</h3>
            <p class="text-xs text-gray-400 mb-5">Kehadiran per departemen hari ini</p>
            @forelse($departments as $role => $dept)
            <div class="mb-4">
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="font-medium text-gray-600">{{ $dept['name'] }}</span>
                    <span class="font-semibold text-[#2c5e4e]">{{ $dept['hadir'] }}/{{ $dept['total'] }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-[#2c5e4e] h-full rounded-full transition-all" style="width: {{ $dept['percentage'] }}%"></div>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="font-semibold text-sm">Belum ada data departemen</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Row 5: Aktivitas Terbaru & Statistik Bulanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        {{-- Aktivitas Terbaru --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Aktivitas Terbaru</h3>
            <p class="text-xs text-gray-400 mb-4">Check in tim hari ini</p>
            <div class="space-y-3 max-h-[300px] overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-[#eaf4f1] rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h5 class="font-semibold text-gray-800 text-sm truncate">{{ $activity->user->name }}</h5>
                        <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($activity->user->role) }} — Check In: {{ $activity->check_in ? \Carbon\Carbon::parse($activity->check_in)->format('H:i') : '-' }} WIB</p>
                    </div>
                    <div class="text-xs text-gray-400 flex-shrink-0">
                        @if($activity->check_in)
                            {{ \Carbon\Carbon::parse($activity->check_in)->diffForHumans() }}
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="font-semibold text-sm">Belum ada aktivitas hari ini</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Statistik Bulanan --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Statistik Bulan Ini</h3>
            <p class="text-xs text-gray-400 mb-5">Ringkasan kinerja {{ now()->translatedFormat('F Y') }}</p>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#eaf4f1] rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Total Kehadiran</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ number_format($monthlyStats['total_kehadiran'] ?? 0) }} kali</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Izin & Sakit</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ number_format($monthlyStats['total_izin_sakit'] ?? 0) }} pengajuan</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Total Panen</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ number_format($monthlyStats['total_panen'] ?? 0, 1) }} kg</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Laporan Cleaning</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ number_format($monthlyStats['total_cleaning'] ?? 0) }} laporan</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Laporan Patroli</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ number_format($monthlyStats['total_patroli'] ?? 0) }} laporan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Updates Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        {{-- Recent Cleaning --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Laporan Cleaning Terbaru
            </h3>
            <div class="space-y-2 max-h-[250px] overflow-y-auto">
                @forelse($recentCleaning ?? [] as $item)
                <div class="p-2 rounded-lg hover:bg-gray-50">
                    <p class="font-medium text-sm text-gray-800">{{ $item->user->name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $item->area }} • {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Belum ada laporan cleaning</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Patroli --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Laporan Patroli Terbaru
            </h3>
            <div class="space-y-2 max-h-[250px] overflow-y-auto">
                @forelse($recentPatroli ?? [] as $item)
                <div class="p-2 rounded-lg hover:bg-gray-50">
                    <p class="font-medium text-sm text-gray-800">{{ $item->user->name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $item->nama_area }} • {{ \Carbon\Carbon::parse($item->waktu_patroli)->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Belum ada laporan patroli</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Panen --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Laporan Panen Terbaru
            </h3>
            <div class="space-y-2 max-h-[250px] overflow-y-auto">
                @forelse($recentPanen ?? [] as $item)
                <div class="p-2 rounded-lg hover:bg-gray-50">
                    <p class="font-medium text-sm text-gray-800">{{ $item->pekerja->name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($item->total_berat_kg ?? 0, 1) }} kg • {{ number_format($item->total_tandan ?? 0) }} tandan • {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Belum ada laporan panen</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Donut Chart Kehadiran
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'],
            datasets: [{
                data: [{{ $hadirHariIni ?? 0 }}, {{ $totalTerlambat ?? 0 }}, {{ $izinHariIni ?? 0 }}, {{ $sakitHariIni ?? 0 }}, {{ $totalAlpha ?? 0 }}],
                backgroundColor: ['#2c5e4e', '#d4a373', '#3b82f6', '#eab308', '#ef4444'],
                borderWidth: 0,
                cutout: '65%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a2e25',
                    padding: 10,
                    titleColor: '#fff',
                    bodyColor: '#a7c4bb'
                }
            }
        }
    });

    // Line Chart Kehadiran 7 Hari
    const lineCtx = document.getElementById('lineAttendanceChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($attendanceChart ?? [])->pluck('tanggal')) !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! json_encode(collect($attendanceChart ?? [])->pluck('hadir')) !!},
                    borderColor: '#2c5e4e',
                    backgroundColor: 'rgba(44, 94, 78, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2c5e4e'
                },
                {
                    label: 'Terlambat',
                    data: {!! json_encode(collect($attendanceChart ?? [])->pluck('terlambat')) !!},
                    borderColor: '#d4a373',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    pointBackgroundColor: '#d4a373'
                },
                {
                    label: 'Izin',
                    data: {!! json_encode(collect($attendanceChart ?? [])->pluck('izin')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6'
                },
                {
                    label: 'Sakit',
                    data: {!! json_encode(collect($attendanceChart ?? [])->pluck('sakit')) !!},
                    borderColor: '#eab308',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    pointBackgroundColor: '#eab308'
                },
                {
                    label: 'Alpha',
                    data: {!! json_encode(collect($attendanceChart ?? [])->pluck('alpha')) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e5e7eb' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Cleaning Chart
    const cleaningCtx = document.getElementById('cleaningChart').getContext('2d');
    new Chart(cleaningCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($cleaningChart ?? [])->pluck('tanggal')) !!},
            datasets: [
                {
                    label: 'Total Laporan',
                    data: {!! json_encode(collect($cleaningChart ?? [])->pluck('total_laporan')) !!},
                    backgroundColor: '#2c5e4e',
                    borderRadius: 8
                },
                {
                    label: 'Total Area',
                    data: {!! json_encode(collect($cleaningChart ?? [])->pluck('total_area')) !!},
                    backgroundColor: '#d4a373',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, grid: { color: '#e5e7eb' } } }
        }
    });

    // Patroli Chart
    const patroliCtx = document.getElementById('patroliChart').getContext('2d');
    new Chart(patroliCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($patroliChart ?? [])->pluck('tanggal')) !!},
            datasets: [
                {
                    label: 'Total Patroli',
                    data: {!! json_encode(collect($patroliChart ?? [])->pluck('total_patroli')) !!},
                    backgroundColor: '#2c5e4e',
                    borderRadius: 8
                },
                {
                    label: 'Total Area',
                    data: {!! json_encode(collect($patroliChart ?? [])->pluck('total_area')) !!},
                    backgroundColor: '#3b82f6',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, grid: { color: '#e5e7eb' } } }
        }
    });

    // Panen Chart (Produksi Panen)
    const panenCtx = document.getElementById('panenChart').getContext('2d');
    new Chart(panenCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($panenChart ?? [])->pluck('tanggal')) !!},
            datasets: [
                {
                    label: 'Total Berat (kg)',
                    data: {!! json_encode(collect($panenChart ?? [])->pluck('total_berat')) !!},
                    borderColor: '#2c5e4e',
                    backgroundColor: 'rgba(44, 94, 78, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2c5e4e',
                    yAxisID: 'y'
                },
                {
                    label: 'Total Tandan',
                    data: {!! json_encode(collect($panenChart ?? [])->pluck('total_tandan')) !!},
                    borderColor: '#d4a373',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    pointBackgroundColor: '#d4a373',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e5e7eb' },
                    title: { display: true, text: 'Berat (kg)', color: '#2c5e4e' }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { display: false },
                    title: { display: true, text: 'Jumlah Tandan', color: '#d4a373' }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endsection