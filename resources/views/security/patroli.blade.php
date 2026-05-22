@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-4 md:p-8">
    <div class="container mx-auto max-w-6xl px-2 sm:px-6">

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

        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button onclick="showTab('input')" id="tab-input-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap">
                Input Patroli
            </button>
            <button onclick="showTab('riwayat')" id="tab-riwayat-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap">
                Riwayat Hari Ini
            </button>
        </div>

        <div id="tab-input" class="tab-content">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex items-center gap-3">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <h2 class="text-base md:text-lg font-semibold text-gray-700">Form Input Patroli</h2>
                </div>
                <div class="p-4 md:p-7">
                    <form action="{{ route('security.patroli.store') }}" method="POST" id="patroliForm">
                        @csrf

                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Area</label>
                            <input type="text" name="nama_area"
                                   class="w-full px-3 md:px-4 py-2 md:py-3 rounded-xl border border-gray-200 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm md:text-base"
                                   placeholder="Contoh: Gudang Belakang, Area Perkebunan Timur, Pos Utama"
                                   required>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan"
                                      class="w-full px-3 md:px-4 py-2 md:py-3 rounded-xl border border-gray-200 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm md:text-base"
                                      rows="3"
                                      placeholder="Kondisi area patroli..."></textarea>
                        </div>

                        {{-- Kamera --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Bukti Patroli</label>
                            
                            <div id="cameraContainer" class="relative rounded-xl overflow-hidden bg-gray-900 w-full md:max-w-2xl mx-auto">
                                <video id="camera" autoplay playsinline class="w-full h-auto md:h-[400px] object-cover"></video>
                                <canvas id="canvas" class="hidden"></canvas>
                                <button type="button" onclick="capturePatroliPhoto()" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/95 hover:bg-white text-[#2c5e4e] font-semibold px-5 md:px-7 py-2 md:py-2.5 rounded-full shadow-lg transition-all text-sm md:text-base whitespace-nowrap flex items-center gap-2">
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ambil Foto
                                </button>
                            </div>

                            {{-- Preview setelah foto diambil --}}
                            <div id="previewContainer" class="hidden mt-3">
                                <div class="bg-[#eaf4f1] rounded-xl p-3 md:p-4">
                                    {{-- Preview foto terakhir --}}
                                    <div class="flex flex-col sm:flex-row items-center gap-3 md:gap-4 mb-3">
                                        <img id="preview" src="" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border-2 border-white shadow" alt="Preview">
                                        <div class="flex-1 text-center sm:text-left">
                                            <p class="font-semibold text-gray-800 text-sm md:text-base">Foto berhasil diambil</p>
                                            <p id="fotoCount" class="text-xs text-green-600 font-semibold mt-1"></p>
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                <button type="button" onclick="tambahFotoLagi()"
                                                    class="text-xs md:text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Tambah Foto Lagi
                                                </button>
                                                <button type="button" onclick="retakePatroliPhoto()"
                                                    class="text-xs md:text-sm text-[#d4a373] hover:text-[#b88352] font-medium flex items-center gap-1">
                                                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Hapus Semua & Ulang
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Grid preview semua foto --}}
                                    <div id="previewGrid" class="grid grid-cols-3 gap-2 mt-2"></div>
                                </div>
                            </div>

                            {{-- Hidden inputs untuk foto array --}}
                            <div id="fotoContainer"></div>
                        </div>

                        <button type="button" onclick="submitPatroli()" id="submitPatroliBtn" disabled
                            class="w-full bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white font-semibold py-2.5 md:py-3 rounded-xl transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kirim Bukti Patroli
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="tab-riwayat" class="tab-content hidden">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <h2 class="text-base md:text-lg font-semibold text-gray-700">Riwayat Patroli Hari Ini</h2>
                    <div class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1.5 md:px-4 md:py-2 rounded-xl font-semibold text-sm md:text-base">
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
                                                <h3 class="text-base md:text-lg font-bold text-gray-800">{{ $item->nama_area }}</h3>
                                                <p class="text-xs md:text-sm text-gray-500 mt-1">
                                                    {{ \Carbon\Carbon::parse($item->waktu_patroli)->format('d M Y - H:i') }}
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
                            <h3 class="text-base md:text-lg font-semibold text-gray-800">Belum Ada Patroli Hari Ini</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">Silakan buka tab Input Patroli untuk memulai</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<script>
let fotoList = [];

// ── TABS ─────────────────────────────────────────────
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
        setTimeout(() => initCamera('camera'), 100);
    } else {
        riwayatBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        inputBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        inputBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        stopCamera(document.getElementById('camera'));
    }
}

// ── CAMERA ───────────────────────────────────────────
async function initCamera(id) {
    const video = document.getElementById(id);
    if (!video) return;
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'environment' }, audio: false 
        });
        video.srcObject = stream;
    } catch (e) {
        console.log('Kamera tidak dapat diakses:', e);
    }
}

function stopCamera(video) {
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(t => t.stop());
        video.srcObject = null;
    }
}

// ── FOTO MULTIPLE ────────────────────────────────────
function capturePatroliPhoto() {
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    if (video.videoWidth === 0) { 
        alert('Kamera belum siap, tunggu sebentar.'); 
        return; 
    }
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const img = canvas.toDataURL('image/jpeg', 0.85);
    
    fotoList.push(img);
    updateFotoUI(img);
    stopCamera(video);
    
    document.getElementById('previewContainer').classList.remove('hidden');
    document.getElementById('cameraContainer').classList.add('hidden');
    document.getElementById('submitPatroliBtn').disabled = false;
}

function updateFotoUI(lastImg) {
    // Update preview gambar terakhir
    document.getElementById('preview').src = lastImg;
    
    // Update counter
    document.getElementById('fotoCount').textContent = fotoList.length + ' foto siap dikirim';
    
    // Update hidden inputs
    const container = document.getElementById('fotoContainer');
    container.innerHTML = fotoList.map(f => `<input type="hidden" name="foto[]" value="${f}">`).join('');
    
    // Update grid preview semua foto
    const grid = document.getElementById('previewGrid');
    grid.innerHTML = fotoList.map((f, i) => `
        <div class="relative">
            <img src="${f}" class="w-full h-20 object-cover rounded-lg border-2 border-white shadow">
            <span class="absolute top-1 left-1 bg-black/50 text-white text-xs px-1.5 py-0.5 rounded">${i + 1}</span>
        </div>
    `).join('');
}

function tambahFotoLagi() {
    document.getElementById('cameraContainer').classList.remove('hidden');
    setTimeout(() => initCamera('camera'), 100);
}

function retakePatroliPhoto() {
    fotoList = [];
    document.getElementById('fotoContainer').innerHTML = '';
    document.getElementById('fotoCount').textContent = '';
    document.getElementById('previewGrid').innerHTML = '';
    document.getElementById('previewContainer').classList.add('hidden');
    document.getElementById('cameraContainer').classList.remove('hidden');
    document.getElementById('submitPatroliBtn').disabled = true;
    initCamera('camera');
}

function submitPatroli() {
    if (fotoList.length === 0) { 
        alert('Silakan ambil foto terlebih dahulu.'); 
        return; 
    }
    const btn = document.getElementById('submitPatroliBtn');
    btn.innerHTML = '<svg class="w-4 h-4 md:w-5 md:h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
    btn.disabled = true;
    document.getElementById('patroliForm').submit();
}

// ── INIT ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    showTab('input');
    setTimeout(() => initCamera('camera'), 100);
});
</script>

<style>
.tab-content { transition: all 0.3s ease; }
</style>
@endsection