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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-0.5">Pengajuan</p>
                        <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Izin / Sakit</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Ajukan izin atau sakit secara online</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs md:text-sm text-gray-500">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, j F Y') }}</p>
                        <span class="inline-block px-3 py-1 md:px-4 md:py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-xs md:text-sm font-medium mt-1">
                            {{ Auth::user()->name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERT MESSAGES --}}
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- FORM PENGAJUAN --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-5 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] bg-gradient-to-r from-[#2c5e4e] to-[#1f4a3d]">
                    <h2 class="text-base md:text-lg font-bold text-white">Form Pengajuan</h2>
                    <p class="text-xs text-white/70 mt-0.5">Isi formulir berikut untuk mengajukan izin atau sakit</p>
                </div>
                <div class="p-5 md:p-6">
                    
                    @if($pengajuanPending)
                        <div class="bg-yellow-50 rounded-xl p-4 text-center border border-yellow-200">
                            <svg class="w-12 h-12 mx-auto text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="font-semibold text-yellow-800 mb-2">Ada Pengajuan Aktif</h3>
                            <p class="text-sm text-yellow-700">Anda sudah memiliki pengajuan yang belum diproses.</p>
                            <p class="text-xs text-yellow-600 mt-2">Tunggu hingga pengajuan saat ini selesai diproses.</p>
                        </div>
                    @else
                        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jenis Pengajuan <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="jenis" value="izin" required class="w-4 h-4 text-[#2c5e4e]" checked>
                                        <span class="text-gray-700">Izin / Cuti</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="jenis" value="sakit" required class="w-4 h-4 text-[#2c5e4e]">
                                        <span class="text-gray-700">Sakit</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" 
                                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" 
                                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition"
                                        required>
                                </div>
                            </div>
                            
                            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="jumlah_hari_info">Total hari: 1 hari</span>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Alasan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alasan" rows="4" 
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition"
                                    placeholder="Jelaskan alasan pengajuan Anda..." required>{{ old('alasan') }}</textarea>
                            </div>
                            
                            <div class="mb-5">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Lampiran (Opsional)
                                </label>
                                <input type="file" name="lampiran" accept="image/*,.pdf" 
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition">
                                <p class="text-xs text-gray-400 mt-1">Upload surat keterangan atau bukti pendukung (jpg, png, pdf, max 2MB)</p>
                            </div>
                            
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-[#2c5e4e] to-[#1f4a3d] hover:from-[#1f4a3d] hover:to-[#163a2e] text-white py-3 rounded-xl font-semibold transition-all transform hover:-translate-y-0.5 shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ajukan Pengajuan
                            </button>
                        </form>
                    @endif
                    
                </div>
            </div>
            
            {{-- RIWAYAT PENGAJUAN --}}
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-5 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base md:text-lg font-bold text-gray-700">Riwayat Pengajuan</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Daftar pengajuan izin dan sakit Anda</p>
                        </div>
                        <div class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1 rounded-full text-sm font-semibold">
                            Total: {{ $riwayatPengajuan->total() }}
                        </div>
                    </div>
                </div>
                <div class="p-5 md:p-6">
                    @if($riwayatPengajuan->count() > 0)
                        <div class="space-y-3">
                            @foreach($riwayatPengajuan as $item)
                            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $item->getJenisBadgeClass() }}">
                                            {{ $item->jenis == 'izin' ? 'Izin / Cuti' : 'Sakit' }}
                                        </span>
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $item->getStatusBadgeClass() }}">
                                            {{ $item->status == 'pending' ? 'Menunggu' : ($item->status == 'disetujui' ? 'Disetujui' : 'Ditolak') }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">
                                        📅 {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                        <span class="text-xs text-gray-400 ml-1">({{ $item->jumlah_hari }} hari)</span>
                                    </p>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">{{ $item->alasan }}</p>
                                @if($item->lampiran)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank" class="text-xs text-blue-500 hover:underline flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat Lampiran
                                    </a>
                                </div>
                                @endif
                                @if($item->catatan_admin)
                                <div class="mt-2 p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500">Catatan Admin:</p>
                                    <p class="text-xs text-gray-600">{{ $item->catatan_admin }}</p>
                                </div>
                                @endif
                                @if($item->status == 'pending')
                                <div class="mt-3 flex justify-end">
                                    <form action="{{ route('pengajuan.batal', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $riwayatPengajuan->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800">Belum Ada Riwayat</h3>
                            <p class="text-sm text-gray-500 mt-1">Anda belum pernah mengajukan izin atau sakit</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
        
        {{-- INFO PANDUAN --}}
        <div class="mt-6 bg-[#eaf4f1]/50 rounded-xl p-4 border border-[#2c5e4e]/10">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-[#2c5e4e] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-[#2c5e4e]">Panduan Pengajuan</p>
                    <ul class="text-xs text-gray-600 mt-1 space-y-1">
                        <li>• Pengajuan izin/cuti dan sakit akan diproses oleh admin</li>
                        <li>• Status pengajuan dapat dilihat di riwayat pengajuan</li>
                        <li>• Pengajuan yang sudah disetujui/ditolak tidak dapat dibatalkan</li>
                        <li>• Lampiran dapat berupa foto atau PDF (maksimal 2MB)</li>
                        <li>• Maksimal pengajuan adalah 30 hari berturut-turut</li>
                        <li>• Pengajuan untuk tanggal yang sudah lewat tidak dapat dilakukan</li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
// Hitung jumlah hari otomatis
const tanggalMulai = document.getElementById('tanggal_mulai');
const tanggalSelesai = document.getElementById('tanggal_selesai');
const jumlahHariInfo = document.getElementById('jumlah_hari_info');

function hitungJumlahHari() {
    if (tanggalMulai.value && tanggalSelesai.value) {
        const mulai = new Date(tanggalMulai.value);
        const selesai = new Date(tanggalSelesai.value);
        const diffTime = Math.abs(selesai - mulai);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        if (diffDays > 30) {
            jumlahHariInfo.innerHTML = '<span class="text-red-600">⚠️ Maksimal 30 hari, silakan perpendek rentang tanggal</span>';
        } else {
            jumlahHariInfo.innerHTML = `Total hari: ${diffDays} hari`;
        }
    }
}

tanggalMulai.addEventListener('change', hitungJumlahHari);
tanggalSelesai.addEventListener('change', hitungJumlahHari);
hitungJumlahHari();

// Validasi tanggal selesai tidak boleh lebih kecil dari tanggal mulai
tanggalSelesai.addEventListener('change', function() {
    if (tanggalMulai.value && this.value && this.value < tanggalMulai.value) {
        this.value = tanggalMulai.value;
        hitungJumlahHari();
        alert('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
    }
});

tanggalMulai.addEventListener('change', function() {
    if (tanggalSelesai.value && this.value > tanggalSelesai.value) {
        tanggalSelesai.value = this.value;
        hitungJumlahHari();
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
    }
});
</script>
@endsection