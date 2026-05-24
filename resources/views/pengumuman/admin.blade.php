@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Admin</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Kelola Pengumuman</h1>
                    <p class="text-sm text-gray-500 mt-1">Tambahkan atau kelola pengumuman untuk seluruh pegawai</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf4f1] text-[#1f4a3d] p-4 px-5 rounded-2xl mb-5 border border-[#2c5e4e]/20 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Form Tambah --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-[#eaf4f1]">
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-700">Tambah Pengumuman</h2>
            </div>

            <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-4">
                @csrf

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Judul</label>
                    <input type="text" name="judul"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e] @error('judul') border-red-400 @enderror"
                        placeholder="Masukkan judul pengumuman" value="{{ old('judul') }}" required>
                    @error('judul') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Isi --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Isi Pengumuman</label>
                    <textarea name="isi" rows="4"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e] @error('isi') border-red-400 @enderror"
                        placeholder="Tulis isi pengumuman" required>{{ old('isi') }}</textarea>
                    @error('isi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Target --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Tujuan Pengumuman</label>

                    <div class="flex gap-5 mb-3">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="target_type" value="all"
                                {{ old('target_type', 'all') === 'all' ? 'checked' : '' }}
                                class="w-4 h-4 accent-[#2c5e4e]"
                                onchange="togglePegawaiBox(false)">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900">Semua Pegawai</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="target_type" value="specific"
                                {{ old('target_type') === 'specific' ? 'checked' : '' }}
                                class="w-4 h-4 accent-[#2c5e4e]"
                                onchange="togglePegawaiBox(true)">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900">Pegawai Tertentu</span>
                        </label>
                    </div>

                    {{-- Selector Pegawai --}}
                    <div id="pegawaiBox"
                        class="{{ old('target_type') === 'specific' ? '' : 'hidden' }} border border-gray-200 rounded-xl overflow-hidden">

                        {{-- Header selector --}}
                        <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 border-b border-gray-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pilih Pegawai</p>
                            <button type="button" id="toggleAllBtn" onclick="toggleAllPegawai()"
                                class="text-xs font-semibold text-[#2c5e4e] hover:text-[#1f4a3d] hover:underline transition-colors">
                                Pilih Semua
                            </button>
                        </div>

                        {{-- Search --}}
                        <div class="px-3 pt-3 pb-2 border-b border-gray-100">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" id="searchPegawai" placeholder="Cari nama pegawai..."
                                    oninput="filterPegawai(this.value)"
                                    class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                            </div>
                        </div>

                        {{-- List --}}
                        <div id="pegawaiList" class="max-h-52 overflow-y-auto divide-y divide-gray-50">
                            @foreach($pegawaiList as $p)
                            <label class="pegawai-item flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 cursor-pointer transition-colors"
                                data-name="{{ strtolower($p->name) }}">
                                <input type="checkbox" name="target_users[]" value="{{ $p->id }}"
                                    {{ is_array(old('target_users')) && in_array($p->id, old('target_users')) ? 'checked' : '' }}
                                    class="w-4 h-4 accent-[#2c5e4e] flex-shrink-0">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-semibold
                                    @switch($p->role)
                                        @case('user') bg-[#eaf4f1] text-[#2c5e4e] @break
                                        @case('mandor') bg-amber-100 text-amber-800 @break
                                        @case('security') bg-blue-100 text-blue-800 @break
                                        @case('cleaning') bg-purple-100 text-purple-800 @break
                                        @case('kantoran') bg-rose-100 text-rose-800 @break
                                        @default bg-gray-100 text-gray-600
                                    @endswitch">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}{{ strtoupper(strpos($p->name, ' ') !== false ? substr($p->name, strpos($p->name, ' ') + 1, 1) : '') }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-800 leading-tight truncate">{{ $p->name }}</p>
                                    <p class="text-xs text-gray-400 capitalize leading-tight mt-0.5">{{ $p->role }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        {{-- Counter --}}
                        <div class="px-3 py-2 bg-gray-50 border-t border-gray-200">
                            <p id="selectedCount" class="text-xs text-gray-400">0 pegawai dipilih</p>
                        </div>
                    </div>
                    @error('target_users')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 shadow-md inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengumuman
                </button>
            </form>
        </div>

        {{-- List Pengumuman --}}
        <div class="space-y-4">
            @forelse($announcements as $a)
            @php $targets = $a->target_users; @endphp
            <div class="bg-white rounded-2xl p-5 md:p-6 border shadow-sm transition-all duration-200 hover:shadow-md
                {{ $targets ? 'border-amber-200 hover:border-amber-300' : 'border-gray-200 hover:border-[#d0e9e3]' }}">

                <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                    <div class="flex items-center flex-wrap gap-2">
                        <h3 class="text-base font-semibold text-gray-800">{{ $a->judul }}</h3>
                        @if($targets)
                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ count($targets) }} pegawai
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-[#eaf4f1] text-[#2c5e4e] border border-[#2c5e4e]/20 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                </svg>
                                Semua pegawai
                            </span>
                        @endif
                    </div>
                    <form action="{{ route('admin.pengumuman.delete', $a->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')"
                            class="border border-red-200 text-red-500 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-red-50 hover:border-red-300 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $a->isi }}</p>

                {{-- Target names --}}
                @if($targets)
                @php $namaTarget = \App\Models\User::whereIn('id', $targets)->pluck('name'); @endphp
                <div class="bg-amber-50 border border-amber-100 rounded-xl px-3 py-2.5 mb-3">
                    <p class="text-xs font-semibold text-amber-800 mb-2">Ditujukan kepada:</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($namaTarget as $nama)
                        <span class="bg-white border border-amber-200 text-amber-900 text-xs font-medium px-2.5 py-1 rounded-full">{{ $nama }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-1.5 text-xs text-gray-400 pt-3 border-t border-gray-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Dibuat pada: {{ $a->created_at->format('d M Y, H:i') }}
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-200">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <p class="font-semibold text-gray-500 mb-1">Belum ada pengumuman</p>
                <p class="text-xs text-gray-400">Silakan tambah pengumuman melalui form di atas</p>
            </div>
            @endforelse
        </div>

    </div>
</div>

<script>
function togglePegawaiBox(show) {
    const box = document.getElementById('pegawaiBox');
    box.classList.toggle('hidden', !show);
    if (!show) {
        document.querySelectorAll('#pegawaiList input[type=checkbox]').forEach(c => c.checked = false);
        updateCount();
    }
}

function filterPegawai(q) {
    document.querySelectorAll('.pegawai-item').forEach(el => {
        el.style.display = el.dataset.name.includes(q.toLowerCase()) ? '' : 'none';
    });
}

function toggleAllPegawai() {
    const cbs = document.querySelectorAll('#pegawaiList input[type=checkbox]');
    const allChecked = Array.from(cbs).every(c => c.checked);
    cbs.forEach(c => c.checked = !allChecked);
    document.getElementById('toggleAllBtn').textContent = allChecked ? 'Pilih Semua' : 'Batal Semua';
    updateCount();
}

function updateCount() {
    const n = document.querySelectorAll('#pegawaiList input[type=checkbox]:checked').length;
    document.getElementById('selectedCount').textContent = n + ' pegawai dipilih';
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#pegawaiList input[type=checkbox]').forEach(cb => {
        cb.addEventListener('change', updateCount);
    });
    updateCount();
});
</script>
@endsection