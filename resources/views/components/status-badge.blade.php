@php
    $statusClasses = [
        'tepat waktu' => 'bg-green-100 text-green-700',
        'hadir' => 'bg-green-100 text-green-700',
        'terlambat' => 'bg-amber-100 text-amber-700',
        'izin' => 'bg-blue-100 text-blue-700',
        'sakit' => 'bg-purple-100 text-purple-700',
        'alpha' => 'bg-red-100 text-red-700',
        
    ];
    
    $statusLabels = [
        'tepat waktu' => 'Hadir',
        'hadir' => 'Hadir',
        'terlambat' => 'Terlambat',
        'izin' => 'Izin',
        'sakit' => 'Sakit',
        'alpha' => 'Alpha',
   
    ];
    
    $statusClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-600';
    $statusLabel = $statusLabels[$status] ?? ucfirst($status);
@endphp

<span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
    {{ $statusLabel }}
</span>