@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-8 pb-5 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Admin</p>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Laporan Kinerja & Kehadiran</h1>
                <p class="text-sm text-gray-500 mt-1">Dashboard analisis produktivitas seluruh role</p>
            </div>
            <div class="flex items-center gap-3 bg-[#eaf4f1] px-4 py-2 rounded-full">
                <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="text-sm font-medium text-[#2c5e4e]">PT. Sipirok Indah</span>
            </div>
        </div>
    </div>

    {{-- TABS NAVIGATION --}}
    <div class="flex flex-wrap gap-1 mb-6 border-b border-gray-200">
        <button onclick="setRole('')" class="tab-role-btn px-5 py-2.5 rounded-t-lg text-sm font-semibold transition-all flex items-center gap-2 {{ empty(request('role')) ? 'bg-[#2c5e4e] text-white shadow-md' : 'text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Semua Role
        </button>
        <button onclick="setRole('user')" class="tab-role-btn px-5 py-2.5 rounded-t-lg text-sm font-semibold transition-all flex items-center gap-2 {{ request('role') == 'user' ? 'bg-[#2c5e4e] text-white shadow-md' : 'text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Pekerja Sawit
        </button>
        <button onclick="setRole('security')" class="tab-role-btn px-5 py-2.5 rounded-t-lg text-sm font-semibold transition-all flex items-center gap-2 {{ request('role') == 'security' ? 'bg-[#2c5e4e] text-white shadow-md' : 'text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Security
        </button>
        <button onclick="setRole('cleaning')" class="tab-role-btn px-5 py-2.5 rounded-t-lg text-sm font-semibold transition-all flex items-center gap-2 {{ request('role') == 'cleaning' ? 'bg-[#2c5e4e] text-white shadow-md' : 'text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.5"></rect>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5h14M5 9h14M5 13h14M5 17h14"></path>
            </svg>
            Cleaning
        </button>
        <button onclick="setRole('kantoran')" class="tab-role-btn px-5 py-2.5 rounded-t-lg text-sm font-semibold transition-all flex items-center gap-2 {{ request('role') == 'kantoran' ? 'bg-[#2c5e4e] text-white shadow-md' : 'text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Kantoran
        </button>
        <button onclick="setRole('mandor')" class="tab-role-btn px-5 py-2.5 rounded-t-lg text-sm font-semibold transition-all flex items-center gap-2 {{ request('role') == 'mandor' ? 'bg-[#2c5e4e] text-white shadow-md' : 'text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Mandor
        </button>
    </div>

    {{-- FILTER BOX --}}
    <form method="GET" action="{{ route('admin.laporan') }}" id="filterForm" class="bg-white rounded-2xl p-5 md:p-6 mb-6 border border-gray-200 shadow-sm">
        <input type="hidden" name="role" id="roleInput" value="{{ request('role', '') }}">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Filter Laporan</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Dari Tanggal
                </label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Sampai Tanggal
                </label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Tampilkan Data
                </label>
                <select name="data_type" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    <option value="today" {{ request('data_type', 'today') == 'today' ? 'selected' : '' }}>Hari Ini Saja</option>
                    <option value="all" {{ request('data_type') == 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit"
                class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Terapkan Filter
            </button>
            <a href="{{ route('admin.laporan') }}"
                class="bg-white text-gray-500 px-5 py-2.5 rounded-xl font-medium text-sm border border-gray-200 hover:border-gray-300 hover:text-gray-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset
            </a>
        </div>
    </form>

    @php
        $selectedRole = request('role');
        $dataType = request('data_type', 'today');
        $todayDate = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    @endphp

   @php
    $gridClass = match($selectedRole) {
        'user'     => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-5',
        'security' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        'cleaning' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        'kantoran' => 'grid-cols-1 sm:grid-cols-2',
        'mandor'   => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-5',
        default    => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-5',
    };
@endphp
<div class="grid {{ $gridClass }} gap-5 mb-8">
    @if(empty($selectedRole))
        {{-- SEMUA ROLE: 5 card --}}
    
      

    @elseif($selectedRole == 'user')
        {{-- USER: 5 card --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Pekerja Sawit</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalPegawai) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: pekerja/helm --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-5.447-3.724M17 20H7m10 0v-1c0-.88-.154-1.726-.447-2.506M7 20H2v-1a4 4 0 015.447-3.724M7 20v-1c0-.88.154-1.726.447-2.506M15 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Brondolan</p>
                    <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($totalBrondolan, 1) }}</p>
                    <p class="text-xs text-gray-400 mt-1">kilogram</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: timbangan/berat --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Janjang</p>
                    <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($totalJanjang ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">tandan buah</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: koleksi/tandan buah --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Rata-rata Panen</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($rataRataBrondolan, 1) }}</p>
                    <p class="text-xs text-gray-400 mt-1">kg / pekerja</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: bar chart naik = produktivitas --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#2c5e4e] rounded-2xl p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/70 mb-2">Total Kehadiran</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($totalHadir ?? 0) }}</p>
                    <p class="text-xs text-white/60 mt-1">{{ $dataType == 'today' ? 'hari ini' : 'periode ini' }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                    {{-- Icon: centang hadir --}}
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

    @elseif($selectedRole == 'security')
        {{-- SECURITY: 4 card --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Security</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalPegawai) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: perisai centang --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Patroli</p>
                    <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($totalPatroli ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">laporan patroli</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: kaki berjalan patroli --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Lokasi Dipatroli</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalLokasiPatroli ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">area unik</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: map pin lokasi --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#2c5e4e] rounded-2xl p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/70 mb-2">Total Kehadiran</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($totalHadir ?? 0) }}</p>
                    <p class="text-xs text-white/60 mt-1">{{ $dataType == 'today' ? 'hari ini' : 'periode ini' }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

    @elseif($selectedRole == 'cleaning')
        {{-- CLEANING: 4 card --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Cleaning</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalPegawai) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: grup pekerja cleaning --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-5.447-3.724M17 20H7m10 0v-1c0-.88-.154-1.726-.447-2.506M7 20H2v-1a4 4 0 015.447-3.724M7 20v-1c0-.88.154-1.726.447-2.506M15 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Kinerja</p>
                    <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($totalKinerja ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">laporan kinerja</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: clipboard list laporan --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Area Dibersihkan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalAreaKinerja ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">area unik</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: sparkles = bersih/kilap --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#2c5e4e] rounded-2xl p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/70 mb-2">Total Kehadiran</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($totalHadir ?? 0) }}</p>
                    <p class="text-xs text-white/60 mt-1">{{ $dataType == 'today' ? 'hari ini' : 'periode ini' }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

    @elseif($selectedRole == 'kantoran')
        {{-- KANTORAN: 2 card --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Kantoran</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalPegawai) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: gedung kantor --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#2c5e4e] rounded-2xl p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/70 mb-2">Total Kehadiran</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($totalHadir ?? 0) }}</p>
                    <p class="text-xs text-white/60 mt-1">{{ $dataType == 'today' ? 'hari ini' : 'periode ini' }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

    @elseif($selectedRole == 'mandor')
        {{-- MANDOR: 5 card --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Mandor</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalPegawai) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: supervisor/mandor --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Verifikasi</p>
                    <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($totalVerifikasiMandor ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">laporan panen</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: clipboard centang = verifikasi --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Berat</p>
                    <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($totalBeratMandor ?? 0, 1) }}</p>
                    <p class="text-xs text-gray-400 mt-1">kilogram</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: timbangan neraca --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Tandan</p>
                    <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($totalTandanMandor ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">tandan buah</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                    {{-- Icon: paket/bundle tandan --}}
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#2c5e4e] rounded-2xl p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/70 mb-2">Total Kehadiran</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($totalHadir ?? 0) }}</p>
                    <p class="text-xs text-white/60 mt-1">{{ $dataType == 'today' ? 'hari ini' : 'periode ini' }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    @endif
</div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
        @if(empty($selectedRole) || $selectedRole == 'user')
        <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                </svg>
                <h3 class="text-sm font-semibold text-gray-700">Hasil Panen (Brondolan) 7 Hari Terakhir</h3>
            </div>
            <p class="text-xs text-gray-400 mb-5 ml-7">Dalam satuan kilogram (kg)</p>
            @if($dailyBrondolan->count())
                @php $maxBrondolan = $dailyBrondolan->max('total_brondolan') ?: 1; @endphp
                <div class="space-y-4">
                    @foreach($dailyBrondolan as $daily)
                    <div>
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span>{{ \Carbon\Carbon::parse($daily->tanggal)->format('d M Y') }}</span>
                            <span class="font-semibold text-gray-700">{{ number_format($daily->total_brondolan, 1) }} kg</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-[#2c5e4e] h-full rounded-full" style="width: {{ ($daily->total_brondolan / $maxBrondolan) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="h-36 flex items-center justify-center text-gray-400 text-sm">Tidak ada data panen</div>
            @endif
        </div>
        @endif

        <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-sm font-semibold text-gray-700">Kehadiran 7 Hari Terakhir</h3>
            </div>
            <p class="text-xs text-gray-400 mb-5 ml-7">Jumlah pekerja hadir per hari</p>
            @if($dailyAttendance->count())
                <div class="space-y-4">
                    @foreach($dailyAttendance as $daily)
                    <div>
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</span>
                            <span class="font-semibold text-gray-700">{{ number_format($daily->total) }} pekerja</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-[#2c5e4e] h-full rounded-full" style="width: {{ ($totalPegawaiRoleCount > 0 ? ($daily->total / $totalPegawaiRoleCount) * 100 : 0) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="h-36 flex items-center justify-center text-gray-400 text-sm">Tidak ada data kehadiran</div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- DATA TABLES - LENGKAP UNTUK SEMUA ROLE --}}
    {{-- ============================================================ --}}

    @if(empty($selectedRole))
        {{-- SEMUA ROLE: Tampilkan 5 tabel --}}
        <div class="space-y-8">
            {{-- TABEL PEKERJA SAWIT --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-[#eaf4f1] px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="font-semibold text-[#2c5e4e]">Pekerja Sawit</h3>
                        <span class="ml-2 text-xs text-gray-500">({{ $detailedAttendances->filter(fn($a) => $a->user?->role == 'user')->count() }} data)</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Brondolan</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Janjang</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $userAttendances = $detailedAttendances->filter(fn($a) => $a->user?->role == 'user'); @endphp
                            @forelse($userAttendances as $a)
                            @php
                                $panen = \App\Models\LaporanPanen::where('pekerja_id', $a->user_id)->whereDate('tanggal', $a->date)->first();
                                $isIzinSakit = ($a->is_izin_sakit ?? false);
                                $izinStatus = $a->jenis_izin_sakit ?? null;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-[#2c5e4e]">{{ $panen ? number_format($panen->brondolan_kg, 1) : '-' }} kg</td>
                                <td class="px-4 py-3 text-sm">{{ $panen ? number_format($panen->janjang) : '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($isIzinSakit)
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ ucfirst($izinStatus) }}
                                        </span>
                                    @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                    @elseif($a->status == 'terlambat')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    @if($panen)
                                    <button onclick="showBuktiKerjaPopup({type:'user', data:{brondolan_kg:{{ $panen->brondolan_kg }}, janjang:{{ $panen->janjang }}, foto:null}})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                        Lihat
                                    </button>
                                    @else
                                    <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center py-8 text-gray-400">Tidak ada data pekerja sawit</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TABEL SECURITY --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-[#eaf4f1] px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <h3 class="font-semibold text-[#2c5e4e]">Security</h3>
                        <span class="ml-2 text-xs text-gray-500">({{ $detailedAttendances->filter(fn($a) => $a->user?->role == 'security')->count() }} data)</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Lokasi Patroli</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Patroli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $securityAttendances = $detailedAttendances->filter(fn($a) => $a->user?->role == 'security'); @endphp
                            @forelse($securityAttendances as $a)
                            @php 
                                $patroli = \App\Models\PatroliSecurity::where('user_id', $a->user_id)->whereDate('created_at', $a->date)->get();
                                $isIzinSakit = ($a->is_izin_sakit ?? false);
                                $izinStatus = $a->jenis_izin_sakit ?? null;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $patroli->pluck('nama_area')->implode(', ') ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($isIzinSakit)
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ ucfirst($izinStatus) }}
                                        </span>
                                    @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                    @elseif($a->status == 'terlambat')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    @if($patroli->count())
                                    <button onclick="showBuktiKerjaPopup({type:'security', data:{patroli: {{ json_encode($patroli->map(fn($p) => ['nama_area' => $p->nama_area, 'keterangan' => $p->keterangan, 'foto' => $p->foto ? asset('storage/'.$p->foto) : null])) }} }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        Lihat ({{ $patroli->count() }})
                                    </button>
                                    @else
                                    <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-8 text-gray-400">Tidak ada data security</td>@endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TABEL CLEANING --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-[#eaf4f1] px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.5"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5h14M5 9h14M5 13h14M5 17h14"></path></svg>
                        <h3 class="font-semibold text-[#2c5e4e]">Cleaning Service</h3>
                        <span class="ml-2 text-xs text-gray-500">({{ $detailedAttendances->filter(fn($a) => $a->user?->role == 'cleaning')->count() }} data)</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Area Cleaning</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $cleaningAttendances = $detailedAttendances->filter(fn($a) => $a->user?->role == 'cleaning'); @endphp
                            @forelse($cleaningAttendances as $a)
                            @php 
                                $kinerja = \App\Models\KinerjaCleaning::where('user_id', $a->user_id)->whereDate('tanggal', $a->date)->get();
                                $isIzinSakit = ($a->is_izin_sakit ?? false);
                                $izinStatus = $a->jenis_izin_sakit ?? null;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $kinerja->pluck('area')->implode(', ') ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($isIzinSakit)
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ ucfirst($izinStatus) }}
                                        </span>
                                    @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                    @elseif($a->status == 'terlambat')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    @if($kinerja->count())
                                    <button onclick="showBuktiKerjaPopup({type:'cleaning', data:{kinerja: {{ json_encode($kinerja->map(fn($k) => ['area' => $k->area, 'keterangan' => $k->keterangan, 'foto' => $k->foto ? asset('storage/'.$k->foto) : null])) }} }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.5"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5h14M5 9h14M5 13h14M5 17h14"></path></svg>
                                        Lihat ({{ $kinerja->count() }})
                                    </button>
                                    @else
                                    <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-8 text-gray-400">Tidak ada data cleaning</td>@endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TABEL KANTORAN --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-[#eaf4f1] px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <h3 class="font-semibold text-[#2c5e4e]">Kantoran</h3>
                        <span class="ml-2 text-xs text-gray-500">({{ $detailedAttendances->filter(fn($a) => $a->user?->role == 'kantoran')->count() }} data)</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $kantoranAttendances = $detailedAttendances->filter(fn($a) => $a->user?->role == 'kantoran'); @endphp
                            @forelse($kantoranAttendances as $a)
                            @php
                                $isIzinSakit = ($a->is_izin_sakit ?? false);
                                $izinStatus = $a->jenis_izin_sakit ?? null;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                                <td class="px-4 py-3">
                                    @if($isIzinSakit)
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ ucfirst($izinStatus) }}
                                        </span>
                                    @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                    @elseif($a->status == 'terlambat')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-8 text-gray-400">Tidak ada data kantoran</td>@endforelse
                        </tbody>
                    </table>
                </div>
            </div>

           {{-- TABEL MANDOR --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="bg-[#eaf4f1] px-6 py-3 border-b border-gray-200">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <h3 class="font-semibold text-[#2c5e4e]">Mandor</h3>
            <span class="ml-2 text-xs text-gray-500">({{ $detailedAttendances->filter(fn($a) => $a->user?->role == 'mandor')->count() }} data)</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Verifikasi</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Total Berat</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Total Tandan</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Kerja</th>
                </tr>
            </thead>
            <tbody>
                @php $mandorAttendances = $detailedAttendances->filter(fn($a) => $a->user?->role == 'mandor'); @endphp
                @forelse($mandorAttendances as $a)
                @php
                    $totalVerifikasi = $a->total_verifikasi ?? 0;
                    $totalBerat = $a->total_berat ?? 0;
                    $totalTandan = $a->total_tandan ?? 0;
                    $isIzinSakit = ($a->is_izin_sakit ?? false);
                    $izinStatus = $a->jenis_izin_sakit ?? null;
                @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                    <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                    <td class="px-4 py-3 text-sm">{{ $totalVerifikasi }} laporan</td>
                    <td class="px-4 py-3 text-sm font-semibold text-[#2c5e4e]">{{ number_format($totalBerat, 1) }} kg</td>
                    <td class="px-4 py-3 text-sm font-semibold text-[#2c5e4e]">{{ number_format($totalTandan) }}</td>
                    <td class="px-4 py-3">
                        @if($isIzinSakit)
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ ucfirst($izinStatus) }}
                            </span>
                        @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                        @elseif($a->status == 'terlambat')
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                        @else
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Lihat
                        </button>
                    </td>
                    <td class="px-4 py-3">
                        @if($totalVerifikasi > 0)
                        <button onclick='showBuktiKerjaMandorPopup({{ json_encode(["total_verifikasi" => $totalVerifikasi, "total_berat" => $totalBerat, "total_tandan" => $totalTandan]) }})' class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Lihat ({{ $totalVerifikasi }})
                        </button>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-8 text-gray-400">Tidak ada data mandor</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    @elseif($selectedRole == 'user')
        {{-- TABEL KHUSUS USER --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <h3 class="font-semibold text-gray-800">Detail Pekerja Sawit</h3>
                </div>
                <span class="text-xs text-gray-400">Total: {{ $detailedAttendances->total() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Brondolan (kg)</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Janjang</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Panen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailedAttendances as $a)
                        @php 
                            $panen = \App\Models\LaporanPanen::where('pekerja_id', $a->user_id)->whereDate('tanggal', $a->date)->first();
                            $isIzinSakit = ($a->is_izin_sakit ?? false);
                            $izinStatus = $a->jenis_izin_sakit ?? null;
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                            <td class="px-4 py-3 font-semibold text-[#2c5e4e]">{{ $panen ? number_format($panen->brondolan_kg, 1) : '-' }} kg</td>
                            <td class="px-4 py-3">{{ $panen ? number_format($panen->janjang) : '-' }}</td>
                            <td class="px-4 py-3">
                                @if($isIzinSakit)
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($izinStatus) }}
                                    </span>
                                @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                @elseif($a->status == 'terlambat')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                @if($panen)
                                <button onclick="showBuktiKerjaPopup({type:'user', data:{brondolan_kg:{{ $panen->brondolan_kg }}, janjang:{{ $panen->janjang }}, foto:null}})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                    Lihat
                                </button>
                                @else
                                <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $detailedAttendances->links() }}</div>
        </div>

    @elseif($selectedRole == 'security')
        {{-- TABEL KHUSUS SECURITY --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <h3 class="font-semibold text-gray-800">Detail Security</h3>
                </div>
                <span class="text-xs text-gray-400">Total: {{ $detailedAttendances->total() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Lokasi Patroli</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Patroli</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailedAttendances as $a)
                        @php 
                            $patroli = \App\Models\PatroliSecurity::where('user_id', $a->user_id)->whereDate('created_at', $a->date)->get();
                            $isIzinSakit = ($a->is_izin_sakit ?? false);
                            $izinStatus = $a->jenis_izin_sakit ?? null;
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $patroli->pluck('nama_area')->implode(', ') ?: '-' }}</td>
                            <td class="px-4 py-3">
                                @if($isIzinSakit)
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($izinStatus) }}
                                    </span>
                                @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                @elseif($a->status == 'terlambat')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                @if($patroli->count())
                                <button onclick="showBuktiKerjaPopup({type:'security', data:{patroli: {{ json_encode($patroli->map(fn($p) => ['nama_area' => $p->nama_area, 'keterangan' => $p->keterangan, 'foto' => $p->foto ? asset('storage/'.$p->foto) : null])) }} }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Lihat ({{ $patroli->count() }})
                                </button>
                                @else
                                <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $detailedAttendances->links() }}</div>
        </div>

    @elseif($selectedRole == 'cleaning')
        {{-- TABEL KHUSUS CLEANING --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.5"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5h14M5 9h14M5 13h14M5 17h14"></path></svg>
                    <h3 class="font-semibold text-gray-800">Detail Cleaning Service</h3>
                </div>
                <span class="text-xs text-gray-400">Total: {{ $detailedAttendances->total() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Area Cleaning</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailedAttendances as $a)
                        @php 
                            $kinerja = \App\Models\KinerjaCleaning::where('user_id', $a->user_id)->whereDate('tanggal', $a->date)->get();
                            $isIzinSakit = ($a->is_izin_sakit ?? false);
                            $izinStatus = $a->jenis_izin_sakit ?? null;
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $kinerja->pluck('area')->implode(', ') ?: '-' }}</td>
                            <td class="px-4 py-3">
                                @if($isIzinSakit)
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($izinStatus) }}
                                    </span>
                                @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                @elseif($a->status == 'terlambat')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                @if($kinerja->count())
                                <button onclick="showBuktiKerjaPopup({type:'cleaning', data:{kinerja: {{ json_encode($kinerja->map(fn($k) => ['area' => $k->area, 'keterangan' => $k->keterangan, 'foto' => $k->foto ? asset('storage/'.$k->foto) : null])) }} }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.5"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5h14M5 9h14M5 13h14M5 17h14"></path></svg>
                                    Lihat ({{ $kinerja->count() }})
                                </button>
                                @else
                                <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                <td>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $detailedAttendances->links() }}</div>
        </div>

    @elseif($selectedRole == 'kantoran')
        {{-- TABEL KHUSUS KANTORAN --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <h3 class="font-semibold text-gray-800">Detail Kantoran</h3>
                </div>
                <span class="text-xs text-gray-400">Total: {{ $detailedAttendances->total() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailedAttendances as $a)
                        @php
                            $isIzinSakit = ($a->is_izin_sakit ?? false);
                            $izinStatus = $a->jenis_izin_sakit ?? null;
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                            <td class="px-4 py-3">
                                @if($isIzinSakit)
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($izinStatus) }}
                                    </span>
                                @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                                @elseif($a->status == 'terlambat')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $detailedAttendances->links() }}</div>
        </div>

  @elseif($selectedRole == 'mandor')
    {{-- TABEL KHUSUS MANDOR --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Detail Mandor</h3>
            </div>
            <span class="text-xs text-gray-400">Total: {{ $detailedAttendances->total() }} data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        @if($dataType == 'all')<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Tanggal</th>@endif
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Nama</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check In</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Check Out</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Verifikasi</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Total Berat</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Total Tandan</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Absen</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Bukti Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailedAttendances as $a)
                    @php
                        $totalVerifikasi = $a->total_verifikasi ?? 0;
                        $totalBerat = $a->total_berat ?? 0;
                        $totalTandan = $a->total_tandan ?? 0;
                        $isIzinSakit = ($a->is_izin_sakit ?? false);
                        $izinStatus = $a->jenis_izin_sakit ?? null;
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        @if($dataType == 'all')<td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>@endif
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $a->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : ($a->check_in ? 'Belum' : '-') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $totalVerifikasi }} laporan</td>
                        <td class="px-4 py-3 text-sm font-semibold text-[#2c5e4e]">{{ number_format($totalBerat, 1) }} kg</td>
                        <td class="px-4 py-3 text-sm font-semibold text-[#2c5e4e]">{{ number_format($totalTandan) }}</td>
                        <td class="px-4 py-3">
                            @if($isIzinSakit)
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $izinStatus == 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ ucfirst($izinStatus) }}
                                </span>
                            @elseif($a->status == 'tepat waktu' || $a->status == 'hadir')
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hadir</span>
                            @elseif($a->status == 'terlambat')
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Terlambat</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Alpha</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="showBuktiAbsenPopup({{ json_encode(['foto_checkin' => $a->photo_path ? asset('storage/'.$a->photo_path) : null, 'foto_checkout' => $a->checkout_photo_path ? asset('storage/'.$a->checkout_photo_path) : null, 'name' => $a->user?->name, 'date' => $a->date, 'check_in' => $a->check_in, 'check_out' => $a->check_out, 'status' => $isIzinSakit ? $izinStatus : ($a->status == 'tepat waktu' ? 'hadir' : $a->status)]) }})" class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            @if($totalVerifikasi > 0)
                            <button onclick='showBuktiKerjaMandorPopup({{ json_encode(["total_verifikasi" => $totalVerifikasi, "total_berat" => $totalBerat, "total_tandan" => $totalTandan]) }})' class="text-xs text-[#2c5e4e] hover:text-[#1f4a3d] font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Lihat ({{ $totalVerifikasi }})
                            </button>
                            @else
                            <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">{{ $detailedAttendances->links() }}</div>
    </div>
    @endif

    {{-- POPUP BUKTI ABSEN --}}
    <div id="buktiAbsenPopup" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[80vh] overflow-y-auto">
            <div class="sticky top-0 bg-[#2c5e4e] px-6 py-4 rounded-t-2xl flex justify-between items-center">
                <div>
                    <h3 class="text-white font-bold text-lg" id="absenPopupTitle">Bukti Absen</h3>
                    <p class="text-white/70 text-sm" id="absenPopupSubtitle"></p>
                </div>
                <button onclick="closeAbsenPopup()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div><span class="text-gray-500">Check In:</span> <span class="font-medium" id="absenCheckIn">-</span></div>
                    <div><span class="text-gray-500">Check Out:</span> <span class="font-medium" id="absenCheckOut">-</span></div>
                    <div><span class="text-gray-500">Status:</span> <span id="absenStatus"></span></div>
                </div>
                <div class="border-t pt-3">
                    <h4 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Foto Bukti
                    </h4>
                    <div class="flex flex-wrap gap-3" id="absenFotoContainer"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- POPUP BUKTI KERJA --}}
    <div id="buktiKerjaPopup" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
            <div class="sticky top-0 bg-[#2c5e4e] px-6 py-4 rounded-t-2xl flex justify-between items-center">
                <div>
                    <h3 class="text-white font-bold text-lg" id="kerjaPopupTitle">Bukti Kerja</h3>
                    <p class="text-white/70 text-sm" id="kerjaPopupSubtitle"></p>
                </div>
                <button onclick="closeKerjaPopup()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6" id="kerjaPopupContent"></div>
        </div>
    </div>

</div>
</div>

<script>
function setRole(role) {
    document.getElementById('roleInput').value = role;
    document.getElementById('filterForm').submit();
}

function showBuktiAbsenPopup(data) {
    document.getElementById('absenPopupTitle').innerText = data.name;
    document.getElementById('absenPopupSubtitle').innerHTML = data.date;
    document.getElementById('absenCheckIn').innerText = data.check_in ? data.check_in.substring(0,5) : '-';
    document.getElementById('absenCheckOut').innerText = data.check_out ? data.check_out.substring(0,5) : (data.check_in ? 'Belum' : '-');
    
    let statusHtml = '';
    if (data.status === 'izin') {
        statusHtml = '<span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Izin</span>';
    } else if (data.status === 'sakit') {
        statusHtml = '<span class="px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-700">Sakit</span>';
    } else if (data.status === 'hadir') {
        statusHtml = '<span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Hadir</span>';
    } else if (data.status === 'terlambat') {
        statusHtml = '<span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Terlambat</span>';
    } else {
        statusHtml = '<span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">Alpha</span>';
    }
    document.getElementById('absenStatus').innerHTML = statusHtml;
    
    const fotoContainer = document.getElementById('absenFotoContainer');
    fotoContainer.innerHTML = '';
    if (data.foto_checkin) {
        fotoContainer.innerHTML += `<a href="${data.foto_checkin}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-[#eaf4f1] text-[#2c5e4e] rounded-lg text-sm font-medium hover:bg-[#d5ecdf] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Foto Check In
        </a>`;
    }
    if (data.foto_checkout) {
        fotoContainer.innerHTML += `<a href="${data.foto_checkout}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Foto Check Out
        </a>`;
    }
    if (!data.foto_checkin && !data.foto_checkout) {
        fotoContainer.innerHTML = '<span class="text-gray-400 text-sm">Tidak ada foto</span>';
    }
    
    document.getElementById('buktiAbsenPopup').classList.remove('hidden');
    document.getElementById('buktiAbsenPopup').classList.add('flex');
}

function closeAbsenPopup() {
    document.getElementById('buktiAbsenPopup').classList.add('hidden');
    document.getElementById('buktiAbsenPopup').classList.remove('flex');
}

function showBuktiKerjaPopup(data) {
    document.getElementById('kerjaPopupTitle').innerText = data.type === 'user' ? 'Detail Panen' : (data.type === 'security' ? 'Detail Patroli' : 'Detail Kinerja Cleaning');
    document.getElementById('kerjaPopupSubtitle').innerHTML = '';
    
    const content = document.getElementById('kerjaPopupContent');
    
    if (data.type === 'user') {
        content.innerHTML = `
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Brondolan</p>
                        <p class="text-2xl font-bold text-[#2c5e4e]">${data.data.brondolan_kg || 0} kg</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Janjang</p>
                        <p class="text-2xl font-bold text-[#2c5e4e]">${data.data.janjang || 0}</p>
                    </div>
                </div>
            </div>
        `;
    } else if (data.type === 'security') {
        let patroliHtml = '';
        data.data.patroli.forEach(p => {
            patroliHtml += `
                <div class="bg-gray-50 rounded-xl p-3 mb-3">
                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        ${p.nama_area}
                    </p>
                    <p class="text-sm text-gray-600 ml-6">${p.keterangan || '-'}</p>
                    ${p.foto ? `<div class="mt-2 ml-6"><a href="${p.foto}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-200 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Lihat Foto</a></div>` : ''}
                </div>
            `;
        });
        content.innerHTML = patroliHtml || '<p class="text-gray-400 text-center">Tidak ada data patroli</p>';
    } else if (data.type === 'cleaning') {
        let kinerjaHtml = '';
        data.data.kinerja.forEach(k => {
            kinerjaHtml += `
                <div class="bg-gray-50 rounded-xl p-3 mb-3">
                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.5"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5h14M5 9h14M5 13h14M5 17h14"></path></svg>
                        ${k.area}
                    </p>
                    <p class="text-sm text-gray-600 ml-6">${k.keterangan || '-'}</p>
                    ${k.foto ? `<div class="mt-2 ml-6"><a href="${k.foto}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm font-medium hover:bg-orange-200 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Lihat Foto</a></div>` : ''}
                </div>
            `;
        });
        content.innerHTML = kinerjaHtml || '<p class="text-gray-400 text-center">Tidak ada data kinerja</p>';
    }
    
    document.getElementById('buktiKerjaPopup').classList.remove('hidden');
    document.getElementById('buktiKerjaPopup').classList.add('flex');
}

function showBuktiKerjaMandorPopup(data) {
    document.getElementById('kerjaPopupTitle').innerText = 'Detail Verifikasi Panen Mandor';
    document.getElementById('kerjaPopupSubtitle').innerHTML = '';
    
    const content = document.getElementById('kerjaPopupContent');
    
    // Gunakan data yang diterima, jangan panggil 'data' lagi
    content.innerHTML = `
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="grid grid-cols-3 gap-6 text-center">
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex justify-center mb-2">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-[#2c5e4e]">${data.total_verifikasi || 0}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Laporan</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex justify-center mb-2">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-[#2c5e4e]">${Number(data.total_berat).toFixed(1) || '0'}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Berat (kg)</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex justify-center mb-2">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-[#2c5e4e]">${Number(data.total_tandan).toLocaleString() || '0'}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Tandan</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('buktiKerjaPopup').classList.remove('hidden');
    document.getElementById('buktiKerjaPopup').classList.add('flex');
}
function closeKerjaPopup() {
    document.getElementById('buktiKerjaPopup').classList.add('hidden');
    document.getElementById('buktiKerjaPopup').classList.remove('flex');
}

document.getElementById('buktiAbsenPopup').addEventListener('click', function(e) {
    if (e.target === this) closeAbsenPopup();
});
document.getElementById('buktiKerjaPopup').addEventListener('click', function(e) {
    if (e.target === this) closeKerjaPopup();
});
</script>
@endsection