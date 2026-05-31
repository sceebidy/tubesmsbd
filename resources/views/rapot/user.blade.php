@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ============================================================ --}}
        {{-- HEADER DENGAN IKON POHON CEMARA --}}
        {{-- ============================================================ --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
              <div class="flex items-center gap-4">
    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Evaluasi Kinerja Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Rekap evaluasi kinerja berdasarkan periode penilaian</p>
    </div>
</div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
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

        @if(session('warning'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="flex-1">
                <p class="font-semibold text-amber-800 text-sm">Peringatan</p>
                <p class="text-sm text-amber-700">{!! session('warning') !!}</p>
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- KONTEN UTAMA --}}
        {{-- ============================================================ --}}
        @if($rapots->isEmpty())
        <div class="bg-white rounded-2xl p-10 text-center border border-gray-200 shadow-sm">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-600 font-semibold text-lg">Belum ada evaluasi kinerja</p>
            <p class="text-gray-400 text-sm mt-1">Admin akan mengirimkan evaluasi setelah periode penilaian selesai.</p>
        </div>
        @else

        <div class="grid grid-cols-1 gap-6">
            @foreach($rapots as $rapot)
            @php
                $detailAbsen = [];
                if ($rapot->detail_absen) {
                    if (is_string($rapot->detail_absen)) {
                        $detailAbsen = json_decode($rapot->detail_absen, true) ?? [];
                    } elseif (is_array($rapot->detail_absen)) {
                        $detailAbsen = $rapot->detail_absen;
                    }
                }
                $dataEvaluasi = [];
                if ($rapot->detail_evaluasi && is_string($rapot->detail_evaluasi)) {
                    $dataEvaluasi = json_decode($rapot->detail_evaluasi, true) ?? [];
                } elseif (is_array($rapot->detail_evaluasi)) {
                    $dataEvaluasi = $rapot->detail_evaluasi;
                }
                $totalJam = 0;
                $hariHadir = 0;
                $totalTerlambat = 0;
                $hasKeterangan = false;
                if (!empty($detailAbsen)) {
                    foreach ($detailAbsen as $absen) {
                        if (isset($absen['jam_kerja']) && $absen['jam_kerja'] > 0) {
                            $totalJam += abs($absen['jam_kerja'] ?? 0);
                            $hariHadir++;
                        }
                        if (isset($absen['status']) && strtolower($absen['status']) == 'terlambat') {
                            $totalTerlambat++;
                        }
                        $keterangan = $absen['keterangan'] ?? $absen['description'] ?? '-';
                        if ($keterangan && $keterangan !== '-' && trim($keterangan) !== '') {
                            $hasKeterangan = true;
                        }
                    }
                }
                $hariHadir = $dataEvaluasi['hari_hadir'] ?? $hariHadir;
                $totalJam = $dataEvaluasi['total_jam'] ?? $totalJam;
                $totalTerlambat = $dataEvaluasi['total_terlambat'] ?? $totalTerlambat;
                $rataJam = $dataEvaluasi['rata_jam_perhari'] ?? ($hariHadir > 0 ? $totalJam / $hariHadir : 0);
            @endphp

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">

                {{-- Header Kartu --}}
                <div class="px-6 py-5 border-b border-gray-100 bg-[#eaf4f1]/30">
                    <div class="flex flex-wrap justify-between items-start gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-gray-800">
                                Evaluasi Periode <span class="text-[#2c5e4e] font-bold">{{ $rapot->periode }}</span>
                            </h2>
                            <p class="text-xs text-gray-400 mt-1">
                                Dibuat: {{ $rapot->created_at->format('d M Y') }}
                                @if($rapot->generated_at)
                                    · Dikirim: {{ $rapot->generated_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($rapot->status == 'draft') bg-gray-100 text-gray-600
                                @elseif($rapot->status == 'dikirim') bg-blue-100 text-blue-700
                                @elseif($rapot->status == 'selesai') bg-[#eaf4f1] text-[#2c5e4e]
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($rapot->status) }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $rapot->tipe == 'evaluasi' ? 'Evaluasi' : 'Standar' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Informasi Pegawai --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-12 h-12 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-[#2c5e4e]">
                                {{ strtoupper(substr($rapot->user->name ?? 'N', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $rapot->user->name ?? 'N/A' }}</h3>
                            <span class="inline-block px-2.5 py-0.5 bg-[#eaf4f1] text-[#2c5e4e] text-xs font-semibold rounded-full mt-1">
                                {{ ucfirst($rapot->user->role ?? 'user') }}
                            </span>
                        </div>
                    </div>

                    {{-- Statistik --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Hari Hadir</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $hariHadir }} <span class="text-xs font-normal text-gray-400">hari</span></p>
                        </div>
                        <div class="bg-[#eaf4f1]/50 rounded-xl p-4 text-center border border-[#eaf4f1]">
                            <p class="text-xs text-gray-500 mb-1">Rata-rata Harian</p>
                            <p class="text-2xl font-bold text-[#2c5e4e]">{{ number_format($rataJam, 2) }} <span class="text-xs font-normal text-gray-400">jam</span></p>
                        </div>
                    </div>

                    {{-- Evaluasi Kerja --}}
                    @if($rapot->evaluasi_kerja)
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Evaluasi Kerja
                        </h3>
                        <div class="bg-[#eaf4f1]/30 rounded-xl p-4 border border-[#eaf4f1]">
                            <p class="text-gray-700 text-sm whitespace-pre-line leading-relaxed">{{ Str::limit($rapot->evaluasi_kerja, 200) }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Saran Perbaikan --}}
                    @if($rapot->saran_perbaikan)
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Saran Perbaikan
                        </h3>
                        <div class="bg-blue-50/30 rounded-xl p-4 border border-blue-100">
                            <p class="text-gray-700 text-sm whitespace-pre-line leading-relaxed">{{ Str::limit($rapot->saran_perbaikan, 200) }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Catatan --}}
                    @if($rapot->catatan)
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Catatan
                        </h3>
                        <div class="bg-amber-50/30 rounded-xl p-4 border border-amber-100">
                            <p class="text-gray-700 text-sm whitespace-pre-line leading-relaxed">{{ Str::limit($rapot->catatan, 200) }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Detail Absen Accordion --}}
                    @if(!empty($detailAbsen))
                    <div class="pt-2">
                        <details class="group">
                            <summary class="cursor-pointer py-2 flex items-center justify-between text-sm font-semibold text-gray-600 hover:text-[#2c5e4e] transition">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Detail Absensi ({{ count($detailAbsen) }} hari)
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>

                            <div class="mt-4 overflow-x-auto rounded-xl border border-gray-200">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 border-b border-gray-100">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Tanggal</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Check In</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Status</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Jam Kerja</th>
                                            @if($hasKeterangan)
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Keterangan</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($detailAbsen as $absen)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-3 py-2 text-gray-700">
                                                @if(isset($absen['tanggal']) && $absen['tanggal'])
                                                    @php
                                                        try {
                                                            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $absen['tanggal'])) {
                                                                echo $absen['tanggal'];
                                                            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $absen['tanggal'])) {
                                                                echo \Carbon\Carbon::parse($absen['tanggal'])->format('d/m/Y');
                                                            } else {
                                                                echo $absen['tanggal'];
                                                            }
                                                        } catch (\Exception $e) {
                                                            echo $absen['tanggal'];
                                                        }
                                                    @endphp
                                                @else -
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 font-medium text-gray-700">
                                                @if(isset($absen['check_in']) && $absen['check_in'] && $absen['check_in'] !== '-')
                                                    @php
                                                        try {
                                                            if (preg_match('/^\d{1,2}:\d{2}$/', $absen['check_in'])) {
                                                                echo $absen['check_in'];
                                                            } else {
                                                                echo \Carbon\Carbon::parse($absen['check_in'])->format('H:i');
                                                            }
                                                        } catch (\Exception $e) {
                                                            echo $absen['check_in'];
                                                        }
                                                    @endphp
                                                @else -
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">
                                                @php
                                                    $status = $absen['status'] ?? 'hadir';
                                                    $statusLower = strtolower($status);
                                                    $statusColor = match($statusLower) {
                                                        'hadir', 'tepat waktu' => 'bg-[#eaf4f1] text-[#2c5e4e]',
                                                        'izin' => 'bg-yellow-100 text-yellow-800',
                                                        'sakit' => 'bg-blue-100 text-blue-800',
                                                        'terlambat' => 'bg-amber-100 text-amber-800',
                                                        'alfa' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-600'
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 font-medium text-gray-700">
                                                @if(isset($absen['jam_kerja']) && $absen['jam_kerja'] > 0)
                                                    {{ number_format($absen['jam_kerja'], 2) }} jam
                                                @else -
                                                @endif
                                            </td>
                                            @if($hasKeterangan)
                                            <td class="px-3 py-2 text-gray-500 text-xs">
                                                {{ $absen['keterangan'] ?? $absen['description'] ?? '-' }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </details>
                    </div>
                    @endif
                </div>

                {{-- Footer Kartu --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex flex-wrap justify-between items-center gap-3">
                    <div class="text-xs text-gray-400 flex items-center gap-3">
                        <span>ID: {{ $rapot->id }}</span>
                        <span>·</span>
                        <span>Evaluator: {{ $rapot->evaluator->name ?? 'Admin' }}</span>
                    </div>
                    @if(Route::has('rapot.show'))
                    <a href="{{ route('rapot.show', $rapot->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white text-xs font-semibold rounded-xl transition">
                        Lihat Detail
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    @elseif(Route::has('admin.rapot.show') && auth()->user()->role == 'admin')
                    <a href="{{ route('admin.rapot.show', $rapot->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white text-xs font-semibold rounded-xl transition">
                        Lihat Detail
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($rapots->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $rapots->links() }}
        </div>
        @endif

        @endif
    </div>
</div>

@push('styles')
<style>
    details summary::-webkit-details-marker { display: none; }
    details summary { list-style: none; }
</style>
@endpush

@push('scripts')
<script>
    @if(session('success'))
    setTimeout(() => { alert('{{ session('success') }}'); }, 100);
    @endif
    @if(session('error'))
    setTimeout(() => { alert('Error: {{ session('error') }}'); }, 100);
    @endif
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#details') {
            const details = document.querySelector('details');
            if (details) details.open = true;
        }
    });
</script>
@endpush
@endsection