@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Informasi</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Pengumuman</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi terbaru untuk Anda</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- List Pengumuman --}}
        @if($announcements->count())
        <div class="space-y-4">
            @foreach($announcements as $a)
            @php $isPersonal = !is_null($a->target_users); @endphp
            <div class="bg-white rounded-2xl p-5 md:p-6 border shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5
                {{ $isPersonal ? 'border-amber-200 hover:border-amber-300' : 'border-gray-200 hover:border-[#d0e9e3]' }}">

                {{-- Title row --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    <h3 class="text-base font-semibold text-gray-800 leading-snug">{{ $a->judul }}</h3>
                    @if($isPersonal)
                    <span class="flex-shrink-0 inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Khusus Anda
                    </span>
                    @endif
                </div>

                <div class="h-px {{ $isPersonal ? 'bg-amber-100' : 'bg-[#eaf4f1]' }} my-3 rounded-full"></div>

                <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ $a->isi }}</p>

                <div class="flex justify-end">
                    <span class="inline-flex items-center gap-1.5 bg-gray-100 px-3 py-1.5 rounded-full text-xs font-medium text-gray-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $a->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl py-12 px-4 text-center border border-gray-200">
            <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <p class="font-semibold text-gray-500 text-sm mb-1">Belum ada pengumuman</p>
            <p class="text-xs text-gray-400">Belum ada pengumuman yang tersedia saat ini</p>
        </div>
        @endif

    </div>
</div>
@endsection