<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\PatroliSecurity;

class SecurityPatroliController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nama_area' => 'required',
        'keterangan' => 'nullable|array',
        'keterangan.*' => 'nullable|string',
        'foto' => 'required|array',
        'foto.*' => 'required|string',
    ]);

    $savedCount = 0;

    foreach ($request->foto as $index => $fotoBase64) {
        if (empty($fotoBase64)) continue;

        $imageData = $fotoBase64;
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
        }
        $imageData = str_replace(' ', '+', $imageData);
        $decoded = base64_decode($imageData);
        if (!$decoded) continue;

        $fileName = 'patroli/' . date('Y/m/d') . '/' . uniqid() . '.jpg';
        Storage::disk('public')->put($fileName, $decoded);

        PatroliSecurity::create([
            'user_id' => Auth::id(),
            'nama_area' => is_array($request->nama_area) ? ($request->nama_area[$index] ?? $request->nama_area[0]) : $request->nama_area,
            'keterangan' => is_array($request->keterangan) ? ($request->keterangan[$index] ?? '') : ($request->keterangan ?? ''),
            'foto' => $fileName,
            'waktu_patroli' => now(),
        ]);

        $savedCount++;
    }

    if ($savedCount === 0) {
        return back()->with('error', 'Tidak ada data yang berhasil disimpan.');
    }

    return back()->with('success', $savedCount . ' bukti patroli berhasil dikirim');
}
}