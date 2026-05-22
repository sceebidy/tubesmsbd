<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\CatatanPanen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        $today = today();

        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $monthlyCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        $monthlyPalmWeight = 0;

        if ($user->role == 'user') {

            $monthlyPalmWeight = CatatanPanen::where('id_pegawai', $user->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('berat_kg');
        }

        $serverTime = now();

        return view('attendance.index', compact(
            'attendanceToday',
            'monthlyCount',
            'monthlyPalmWeight',
            'serverTime'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();

        $today = today();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'photo' => 'required|string',

            'checkin_latitude' => 'required|numeric',

            'checkin_longitude' => 'required|numeric',

            'checkin_address' => 'nullable|string',

            'note' => 'nullable|string|max:500',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SUDAH ABSEN?
        |--------------------------------------------------------------------------
        */

        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {

            return back()->with(
                'error',
                'Anda sudah check in hari ini'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FOTO
        |--------------------------------------------------------------------------
        */

        if (!str_contains($request->photo, 'base64')) {

            return back()->with(
                'error',
                'Foto check in tidak valid'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $checkInTime = now();

      if (in_array($user->role, ['security', 'cleaning'])) {
    $status = 'tepat waktu'; // Shift fleksibel, tidak ada terlambat
} else {
    $status = $checkInTime->format('H:i') <= '08:00'
        ? 'tepat waktu'
        : 'terlambat';
}

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO
        |--------------------------------------------------------------------------
        */

        $image = $request->photo;

        $image = str_replace(
            'data:image/jpeg;base64,',
            '',
            $image
        );

        $image = str_replace(' ', '+', $image);

        $imageName = 'checkin_' . time() . '.jpg';

        Storage::disk('public')->put(
            'attendance_photos/' . $imageName,
            base64_decode($image)
        );

        $photoPath = 'attendance_photos/' . $imageName;

        /*
        |--------------------------------------------------------------------------
        | ADDRESS DARI JAVASCRIPT
        |--------------------------------------------------------------------------
        */

        $checkinAddress = $request->checkin_address;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATABASE
        |--------------------------------------------------------------------------
        */

        Attendance::create([

            'user_id' => $user->id,

            'date' => $today,

            'check_in' => $checkInTime,

            'status' => $status,

            'photo_path' => $photoPath,

            'checkin_latitude' => $request->checkin_latitude,

            'checkin_longitude' => $request->checkin_longitude,

            'checkin_address' => $checkinAddress,

            'note' => $request->note,
        ]);

        return back()->with(
            'success',
            'Check In berhasil'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $today = today();

        /*
        |--------------------------------------------------------------------------
        | CARI ABSENSI
        |--------------------------------------------------------------------------
        */

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {

            return back()->with(
                'error',
                'Anda belum check in'
            );
        }

        if ($attendance->check_out) {

            return back()->with(
                'error',
                'Anda sudah check out'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        if ($user->role == 'user') {

            $request->validate([

                'checkout_photo' => 'required|string',

                'checkout_latitude' => 'required|numeric',

                'checkout_longitude' => 'required|numeric',

                'checkout_address' => 'nullable|string',

                'palm_weight' => 'required|numeric|min:0',

                'note' => 'nullable|string|max:500',
            ]);

        } else {

            $request->validate([

                'checkout_photo' => 'required|string',

                'checkout_latitude' => 'required|numeric',

                'checkout_longitude' => 'required|numeric',

                'checkout_address' => 'nullable|string',

                'note' => 'required|string|max:500',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FOTO
        |--------------------------------------------------------------------------
        */

        if (!str_contains($request->checkout_photo, 'base64')) {

            return back()->with(
                'error',
                'Foto checkout tidak valid'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO CHECKOUT
        |--------------------------------------------------------------------------
        */

        $checkoutPhoto = $request->checkout_photo;

        $checkoutPhoto = str_replace(
            'data:image/jpeg;base64,',
            '',
            $checkoutPhoto
        );

        $checkoutPhoto = str_replace(' ', '+', $checkoutPhoto);

        $imageName = 'checkout_' . time() . '.jpg';

        Storage::disk('public')->put(
            'checkout_photos/' . $imageName,
            base64_decode($checkoutPhoto)
        );

        $checkoutPhotoPath =
            'checkout_photos/' . $imageName;

        /*
        |--------------------------------------------------------------------------
        | ADDRESS DARI JAVASCRIPT
        |--------------------------------------------------------------------------
        */

        $checkoutAddress = $request->checkout_address;

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $updateData = [

            'check_out' => now(),

            'checkout_photo_path' => $checkoutPhotoPath,

            'checkout_latitude' => $request->checkout_latitude,

            'checkout_longitude' => $request->checkout_longitude,

            'checkout_address' => $checkoutAddress,

            'note' => $request->note,
        ];

        /*
        |--------------------------------------------------------------------------
        | PEKERJA SAWIT
        |--------------------------------------------------------------------------
        */

        if ($user->role == 'user') {

            $updateData['palm_weight']
                = $request->palm_weight;

            CatatanPanen::create([

                'id_pegawai' => $user->id,

                'tanggal' => $today,

                'id_area_kerja' =>
                    $user->id_area_kerja ?? null,

                'jumlah_tandan' => 0,

                'berat_kg' =>
                    $request->palm_weight,

                'catatan' =>
                    $request->note,

                'foto_panen' =>
                    $checkoutPhotoPath,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE ABSENSI
        |--------------------------------------------------------------------------
        */

        $attendance->update($updateData);

        return back()->with(
            'success',
            'Check Out berhasil'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $user = Auth::user();

        $riwayat = Attendance::where(
                'user_id',
                $user->id
            )
            ->latest('date')
            ->paginate(10);

        return view(
            'attendance.history',
            compact('riwayat')
        );
    }
}