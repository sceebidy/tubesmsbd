@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ============================================================ --}}
        {{-- HEADER --}}
        {{-- ============================================================ --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Pengumuman</h1>
                        <p class="text-sm text-gray-500 mt-1">Informasi terbaru untuk Anda</p>
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
        {{-- LIST PENGUMUMAN --}}
        {{-- ============================================================ --}}
        @php
            $userRole = auth()->user()->role;
            $roleLabels = [
                'user'     => 'User',
                'mandor'   => 'Mandor',
                'security' => 'Security',
                'cleaning' => 'Cleaning',
                'kantoran' => 'Kantoran',
            ];
        @endphp

        @if($announcements->count())
        <div class="space-y-4">
            @foreach($announcements as $announcement)
            @php
                $isPersonal = !is_null($announcement->target_users);
                $isRoleTarget = !$isPersonal && !is_null($announcement->target_roles);

                $cardBorder = $isPersonal
                    ? 'border-amber-200 hover:border-amber-300'
                    : ($isRoleTarget ? 'border-blue-200 hover:border-blue-300' : 'border-gray-200 hover:border-[#d0e9e3]');
            @endphp

            <div class="bg-white rounded-2xl p-5 md:p-6 border shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 {{ $cardBorder }}">

                {{-- Title row --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 leading-snug">{{ $announcement->judul }}</h3>
                    </div>

                    {{-- Badge: Khusus Anda (by user ID) --}}
                    @if($isPersonal)
                    <span class="flex-shrink-0 inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Khusus Anda
                    </span>

                    {{-- Badge: Khusus Role (by role) --}}
                    @elseif($isRoleTarget)
                    <span class="flex-shrink-0 inline-flex items-center gap-1 bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        {{ $roleLabels[$userRole] ?? ucfirst($userRole) }}
                    </span>
                    @endif
                </div>

                <div class="h-px {{ $isPersonal ? 'bg-amber-100' : ($isRoleTarget ? 'bg-blue-100' : 'bg-[#eaf4f1]') }} my-3 rounded-full"></div>

                <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ $announcement->isi }}</p>

                <div class="flex flex-wrap justify-between items-center gap-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 px-3 py-1.5 rounded-full text-xs font-medium text-gray-500">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $announcement->created_at->diffForHumans() }}
                        </span>
                        @if($announcement->created_at != $announcement->updated_at)
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 px-3 py-1.5 rounded-full text-xs font-medium text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Diperbarui
                        </span>
                        @endif
                    </div>

                    {{-- Label bawah sesuai jenis target --}}
                    @if($isPersonal)
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-[#eaf4f1] text-[#2c5e4e]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Personal
                    </span>
                    @elseif($isRoleTarget)
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                        </svg>
                        Grup {{ $roleLabels[$userRole] ?? ucfirst($userRole) }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl py-12 px-4 text-center border border-gray-200">
            <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <p class="font-semibold text-gray-500 text-sm mb-1">Belum ada pengumuman</p>
            <p class="text-xs text-gray-400">Belum ada pengumuman yang tersedia saat ini</p>
        </div>
        @endif

    </div>
</div>
@endsection