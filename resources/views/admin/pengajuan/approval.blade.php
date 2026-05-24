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
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-0.5">Administrator</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Approval Izin & Sakit</h1>
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

        {{-- ============================================================ --}}
        {{-- STATISTIK CARD --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Pengajuan</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $pengajuan->total() }}
                            <span class="text-base font-medium text-gray-400">Pengajuan</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Menunggu</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">
                            {{ $pengajuan->where('status', 'pending')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Disetujui</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">
                            {{ $pengajuan->where('status', 'disetujui')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ditolak</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">
                            {{ $pengajuan->where('status', 'ditolak')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB NAVIGATION --}}
        {{-- ============================================================ --}}
        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button onclick="showTab('pending')" id="tab-pending-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap">
                Menunggu Persetujuan
                @php $pendingCount = $pengajuan->where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-1 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </button>
            <button onclick="showTab('riwayat')" id="tab-riwayat-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap">
                Riwayat Pengajuan
            </button>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: MENUNGGU PERSETUJUAN --}}
        {{-- ============================================================ --}}
        <div id="tab-pending" class="tab-content">
            
            {{-- Filter Tambahan --}}
            <div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] mb-6 shadow-sm">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter Role
                        </label>
                        <div class="relative">
                            <select id="filterRole" onchange="filterPendingTable()" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition appearance-none bg-white text-gray-700">
                                <option value="all">Semua Role</option>
                                <option value="user">Pekerja Sawit</option>
                                <option value="cleaning">Cleaning Service</option>
                                <option value="security">Security</option>
                                <option value="kantoran">Karyawan Kantor</option>
                                 <option value="mandor">Mandor</option>  
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor"viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Filter Jenis
                        </label>
                        <div class="relative">
                            <select id="filterJenis" onchange="filterPendingTable()" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition appearance-none bg-white text-gray-700">
                                <option value="all">Semua Jenis</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor"viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <button onclick="resetFilters()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all border border-[#E2E8F0]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            {{-- CARD GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="pendingGrid">
                @php $pendingItems = $pengajuan->where('status', 'pending'); @endphp
                @forelse($pendingItems as $item)
                <div class="pengajuan-card bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden hover:shadow-md transition-all duration-300" 
                     data-role="{{ $item->user->role }}" 
                     data-jenis="{{ $item->jenis }}">
                    
                    <div class="p-5 border-b border-[#E2E8F0]">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#2c5e4e] to-[#3d7a64] flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">{{ $item->user->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        @php
                                            $roleNames = [
                                                'user' => 'Pekerja Sawit',
                                                'cleaning' => 'Cleaning Service',
                                                'kantoran' => 'Karyawan Kantor',
                                                'security' => 'Security',
                                                'mandor' => 'Mandor',
                                            ];
                                            $roleName = $roleNames[$item->user->role] ?? $item->user->role;
                                        @endphp
                                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">{{ $roleName }}</span>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $item->jenis == 'izin' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span class="flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                                Menunggu
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5 space-y-4">
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d F Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $item->jumlah_hari }} hari</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Alasan</p>
                            <p class="text-sm text-gray-700">{{ $item->alasan }}</p>
                        </div>
                        
                        @if($item->lampiran)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <a href="{{ Storage::url($item->lampiran) }}" target="_blank" class="text-sm text-blue-500 hover:underline">Lihat Lampiran</a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-4 bg-gray-50 border-t border-[#E2E8F0] flex gap-3">
                        <form action="{{ route('admin.pengajuan.approve', $item->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Setujui
                            </button>
                        </form>
                        <button onclick="showRejectForm({{ $item->id }}, '{{ $item->user->name }}')" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="text-center py-16 bg-white rounded-2xl border border-[#E2E8F0]">
                        <div class="w-20 h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Pengajuan Menunggu</h3>
                        <p class="text-gray-500">Semua pengajuan sudah diproses</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: RIWAYAT PENGAJUAN --}}
        {{-- ============================================================ --}}
        <div id="tab-riwayat" class="tab-content hidden">
            
            {{-- Filter Riwayat --}}
            <div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] mb-6 shadow-sm">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter Status
                        </label>
                        <div class="relative">
                            <select id="filterRiwayatStatus" onchange="filterRiwayatTable()" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition appearance-none bg-white text-gray-700">
                                <option value="all">Semua Status</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor"viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Filter Role
                        </label>
                        <div class="relative">
                            <select id="filterRiwayatRole" onchange="filterRiwayatTable()" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition appearance-none bg-white text-gray-700">
                                <option value="all">Semua Role</option>
                                <option value="user">Pekerja Sawit</option>
                                <option value="cleaning">Cleaning Service</option>
                                <option value="security">Security</option>
                                <option value="kantoran">Karyawan Kantor</option>
                                    <option value="mandor">Mandor</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor"viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Cari Nama
                        </label>
                        <div class="relative">
                            <input type="text" id="filterRiwayatNama" onkeyup="filterRiwayatTable()" placeholder="Cari nama karyawan..." class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition text-gray-700">
                        </div>
                    </div>

                    <div>
                        <button onclick="resetRiwayatFilters()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all border border-[#E2E8F0]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="riwayatTable">
                        <thead class="bg-gray-50 border-b border-[#eaf4f1]">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">No</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Pemohon</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Role</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Jenis</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Tanggal</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Jml Hari</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Alasan</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Status</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Disetujui Oleh</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatTableBody">
                            @php $riwayatItems = $pengajuan->whereIn('status', ['disetujui', 'ditolak']); @endphp
                            @forelse($riwayatItems as $key => $item)
                            <tr class="riwayat-row border-b border-gray-100 hover:bg-gray-50 transition" 
                                data-status="{{ $item->status }}"
                                data-role="{{ $item->user->role }}"
                                data-name="{{ strtolower($item->user->name) }}">
                                <td class="px-5 py-4 text-gray-600">{{ $key + 1 }}</td>
                                <td class="px-5 py-4 font-medium text-gray-900">{{ $item->user->name }}</td>
                                <td class="px-5 py-4">
                                    @php
                                        $roleNames = [
                                            'user' => 'Pekerja Sawit',
                                            'cleaning' => 'Cleaning',
                                            'kantoran' => 'Kantoran',
                                            'security' => 'Security',
                                            'mandor' => 'Mandor'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        {{ $roleNames[$item->user->role] ?? $item->user->role }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $item->jenis == 'izin' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                                        {{ ucfirst($item->jenis) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-4 text-gray-600">{{ $item->jumlah_hari }} hari</td>
                                <td class="px-5 py-4 text-gray-500 max-w-xs truncate">{{ $item->alasan }}</td>
                                <td class="px-5 py-4">
                                    @if($item->status == 'disetujui')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Disetujui</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-500">{{ $item->approver->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-5 py-10 text-center text-gray-500">Belum ada riwayat pengajuan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL TOLAK --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-6 w-full max-w-md bg-white rounded-2xl shadow-xl">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Tolak Pengajuan</h3>
            <p class="text-sm text-gray-500 mt-1" id="rejectUserName"></p>
            <form id="rejectForm" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 text-left mb-1">Alasan Penolakan</label>
                    <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-[#2c5e4e] focus:border-[#2c5e4e]" placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 rounded-xl transition">Tolak</button>
                    <button type="button" onclick="closeRejectForm()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 rounded-xl transition">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.tab-content {
    transition: all 0.3s ease;
}
.filter-btn {
    transition: all 0.2s ease;
}
#successMessage {
    transition: opacity 0.3s ease;
}
</style>

<script>
// Tab switching
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`tab-${tab}`).classList.remove('hidden');

    const pendingBtn = document.getElementById('tab-pending-btn');
    const riwayatBtn = document.getElementById('tab-riwayat-btn');

    if (tab === 'pending') {
        pendingBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        pendingBtn.classList.remove('text-gray-600', 'bg-transparent');
        riwayatBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.add('text-gray-600', 'bg-transparent');
    } else {
        riwayatBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.remove('text-gray-600', 'bg-transparent');
        pendingBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        pendingBtn.classList.add('text-gray-600', 'bg-transparent');
    }
}

// Filter pending cards
function filterPendingTable() {
    const role = document.getElementById('filterRole').value;
    const jenis = document.getElementById('filterJenis').value;
    const cards = document.querySelectorAll('#pendingGrid .pengajuan-card');
    
    cards.forEach(card => {
        const cardRole = card.getAttribute('data-role');
        const cardJenis = card.getAttribute('data-jenis');
        
        let show = true;
        if (role !== 'all' && cardRole !== role) show = false;
        if (jenis !== 'all' && cardJenis !== jenis) show = false;
        
        card.style.display = show ? '' : 'none';
    });
}

// Filter riwayat table
function filterRiwayatTable() {
    const status = document.getElementById('filterRiwayatStatus').value;
    const role = document.getElementById('filterRiwayatRole').value;
    const nama = document.getElementById('filterRiwayatNama').value.toLowerCase();
    const rows = document.querySelectorAll('#riwayatTableBody .riwayat-row');
    
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        const rowRole = row.getAttribute('data-role');
        const rowName = row.getAttribute('data-name');
        
        let show = true;
        if (status !== 'all' && rowStatus !== status) show = false;
        if (role !== 'all' && rowRole !== role) show = false;
        if (nama && !rowName.includes(nama)) show = false;
        
        row.style.display = show ? '' : 'none';
    });
    
    // Check for empty result
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    let emptyMessage = document.getElementById('emptyRiwayatMessage');
    
    if (visibleRows.length === 0 && rows.length > 0) {
        if (!emptyMessage) {
            const tbody = document.getElementById('riwayatTableBody');
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyRiwayatMessage';
            emptyRow.innerHTML = '<td colspan="9"><div class="text-center py-12"><div class="w-16 h-16 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div><h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Data</h3><p class="text-gray-500">Tidak ditemukan riwayat pengajuan dengan filter yang dipilih</p></div></td>';
            tbody.appendChild(emptyRow);
        }
    } else if (emptyMessage) {
        emptyMessage.remove();
    }
}

function resetFilters() {
    document.getElementById('filterRole').value = 'all';
    document.getElementById('filterJenis').value = 'all';
    filterPendingTable();
}

function resetRiwayatFilters() {
    document.getElementById('filterRiwayatStatus').value = 'all';
    document.getElementById('filterRiwayatRole').value = 'all';
    document.getElementById('filterRiwayatNama').value = '';
    filterRiwayatTable();
}

function showRejectForm(id, name) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectUserName').innerHTML = 'Menolak pengajuan dari: <strong>' + name + '</strong>';
    document.getElementById('rejectForm').action = '/admin/pengajuan/' + id + '/reject';
    document.body.style.overflow = 'hidden';
}

function closeRejectForm() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal on outside click
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectForm();
    }
});

// Set default tab
document.addEventListener('DOMContentLoaded', function() {
    showTab('pending');
});
</script>
@endsection