@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 md:p-8">
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-wide mb-1">Admin</p>
                <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Data Pegawai</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Daftar lengkap pegawai perusahaan</p>
            </div>
            <span class="inline-block px-3 py-1 md:px-4 md:py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-xs md:text-sm font-medium self-start sm:self-center">
                PT. Sipirok Indah
            </span>
        </div>
    </div>

    {{-- Summary Cards - 2 baris x 4 kolom (seimbang) --}}
    {{-- Baris 1 --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 md:gap-5 mb-3 md:mb-5">
        {{-- Total Pegawai --}}
        <div class="bg-[#2c5e4e] rounded-xl md:rounded-2xl p-3 md:p-5 transition-all hover:bg-[#1f4a3d] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-white/70 mb-1 md:mb-2">Total</p>
            <p class="text-xl md:text-3xl font-bold text-white">{{ $pegawai->count() }}</p>
            <p class="text-[9px] md:text-xs text-white/50 mt-0.5 md:mt-1">Semua Pegawai</p>
        </div>

        {{-- Admin --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Admin</p>
            <p class="text-xl md:text-3xl font-bold text-red-600">{{ $pegawai->where('role','admin')->count() }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Administrator</p>
        </div>

        {{-- Manager --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Manager</p>
            <p class="text-xl md:text-3xl font-bold text-purple-600">{{ $pegawai->where('role','manager')->count() }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Manajer</p>
        </div>

        {{-- Mandor --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Mandor</p>
            <p class="text-xl md:text-3xl font-bold text-orange-600">{{ $pegawai->where('role','mandor')->count() }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Pengawas Lapangan</p>
        </div>
    </div>

    {{-- Baris 2 --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 md:gap-5 mb-6 md:mb-8">
        {{-- Pekerja Sawit --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Pekerja</p>
            <p class="text-xl md:text-3xl font-bold text-green-600">{{ $pegawai->where('role','user')->count() }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Pekerja Sawit</p>
        </div>

        {{-- Security --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Security</p>
            <p class="text-xl md:text-3xl font-bold text-blue-600">{{ $pegawai->where('role','security')->count() }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Satpam</p>
        </div>

        {{-- Cleaning --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Cleaning</p>
            <p class="text-xl md:text-3xl font-bold text-yellow-600">{{ $pegawai->where('role','cleaning')->count() }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Kebersihan</p>
        </div>

        {{-- Kantoran --}}
        <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1 md:mb-2">Kantoran</p>
            <p class="text-xl md:text-3xl font-bold text-gray-600">{{ $pegawai->where('role','kantoran')->count() }}</p>
            <p class="text-[9px] md:text-xs text-gray-400 mt-0.5 md:mt-1">Staff Kantor</p>
        </div>
    </div>

    {{-- Filter Box --}}
    <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 mb-6 border border-gray-200 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Cari Pegawai</label>
                <input type="text" id="searchInput" placeholder="Cari nama, email, atau nomor HP..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Filter Role</label>
                <select id="roleFilter"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="mandor">Mandor</option>
                    <option value="user">Pekerja Sawit</option>
                    <option value="security">Security</option>
                    <option value="cleaning">Cleaning Service</option>
                    <option value="kantoran">Staff Kantor</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">&nbsp;</label>
                <button onclick="resetFilters()" 
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-3 py-2.5 text-sm font-medium transition-all border border-gray-200">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl md:rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center gap-3 px-4 md:px-6 py-4 md:py-5 border-b border-gray-100">
            <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-700">Daftar Pegawai</h2>
            <span class="ml-auto text-xs text-gray-400" id="employeeCount">Total: {{ $pegawai->count() }} pegawai</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-3 md:px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">No</th>
                        <th class="text-left px-3 md:px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Nama</th>
                        <th class="text-left px-3 md:px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                        <th class="text-left px-3 md:px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">No HP</th>
                        <th class="text-left px-3 md:px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Role</th>
                        <th class="text-left px-3 md:px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Bergabung</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    @forelse($pegawai as $i => $emp)
                    <tr class="employee-row border-b border-gray-100 hover:bg-gray-50 transition"
                        data-role="{{ $emp->role }}"
                        data-name="{{ strtolower($emp->name) }}"
                        data-email="{{ strtolower($emp->email) }}"
                        data-phone="{{ strtolower($emp->phone ?? $emp->no_hp ?? '') }}">
                        <td class="px-3 md:px-4 py-3 text-sm font-semibold text-gray-600">{{ $i + 1 }}</td>
                        <td class="px-3 md:px-4 py-3">
                            <div class="flex items-center gap-2 md:gap-3">
                                <div class="w-8 h-8 md:w-9 md:h-9 bg-[#eaf4f1] rounded-xl flex items-center justify-center font-bold text-[#2c5e4e] text-xs md:text-sm flex-shrink-0">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-xs md:text-sm">{{ $emp->name }}</div>
                                    <div class="text-[10px] md:text-xs text-gray-400">ID: {{ $emp->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 md:px-4 py-3 text-xs md:text-sm text-gray-600">{{ $emp->email }}</td>
                        <td class="px-3 md:px-4 py-3 text-xs md:text-sm text-gray-600">
                            @if(!empty($emp->phone) || !empty($emp->no_hp))
                                {{ $emp->phone ?? $emp->no_hp }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-3 md:px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 md:px-3 md:py-1 rounded-full text-[10px] md:text-xs font-semibold 
                                @if($emp->role == 'admin') bg-red-100 text-red-700
                                @elseif($emp->role == 'manager') bg-purple-100 text-purple-700
                                @elseif($emp->role == 'mandor') bg-orange-100 text-orange-700
                                @elseif($emp->role == 'user') bg-green-100 text-green-700
                                @elseif($emp->role == 'security') bg-blue-100 text-blue-700
                                @elseif($emp->role == 'cleaning') bg-yellow-100 text-yellow-700
                                @elseif($emp->role == 'kantoran') bg-gray-100 text-gray-700
                                @else bg-[#eaf4f1] text-[#2c5e4e] @endif">
                                @if($emp->role == 'user') Pekerja Sawit
                                @elseif($emp->role == 'security') Security
                                @elseif($emp->role == 'cleaning') Cleaning
                                @elseif($emp->role == 'kantoran') Staff Kantor
                                @elseif($emp->role == 'mandor') Mandor
                                @else {{ ucfirst($emp->role) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-3 md:px-4 py-3 text-xs md:text-sm text-gray-500">{{ $emp->created_at ? $emp->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="font-semibold text-sm text-gray-500">Tidak ada data pegawai</p>
                            <p class="text-xs text-gray-400 mt-1">Silakan tambahkan pegawai baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const rows = document.querySelectorAll('.employee-row');
    const countElement = document.getElementById('employeeCount');

    function filter() {
        const search = searchInput.value.toLowerCase();
        const role = roleFilter.value;
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const phone = row.dataset.phone || '';
            const r = row.dataset.role;

            const matchSearch = name.includes(search) || email.includes(search) || phone.includes(search);
            const matchRole = !role || r === role;

            if (matchSearch && matchRole) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update tampilan jumlah
        if (countElement) {
            if (search || role) {
                countElement.innerHTML = `Menampilkan: ${visibleCount} dari ${rows.length} pegawai`;
            } else {
                countElement.innerHTML = `Total: ${rows.length} pegawai`;
            }
        }
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (roleFilter) roleFilter.value = '';
        filter();
    }

    if (searchInput) searchInput.addEventListener('input', filter);
    if (roleFilter) roleFilter.addEventListener('change', filter);
    
    // Export reset function ke global
    window.resetFilters = resetFilters;
});
</script>
@endsection