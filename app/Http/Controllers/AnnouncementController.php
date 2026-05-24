<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Events\NewAnnouncementEvent;

class AnnouncementController extends Controller
{
    // =================== ADMIN ===================
    public function indexAdmin()
    {
        $announcements = Announcement::latest()->get();
        $pegawaiList   = User::whereNotIn('role', ['admin', 'manager'])
                             ->orderBy('name')
                             ->get();

        return view('pengumuman.admin', compact('announcements', 'pegawaiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'target_type'  => 'required|in:all,specific',
            'target_users' => 'required_if:target_type,specific|array|min:1',
            'target_users.*' => 'exists:users,id',
        ], [
            'target_users.required_if' => 'Pilih minimal satu pegawai yang dituju.',
        ]);

        $targetUsers = $request->target_type === 'specific'
            ? $request->target_users
            : null;

        $announcement = Announcement::create([
            'judul'        => $request->judul,
            'isi'          => $request->isi,
            'created_by'   => auth()->id(),
            'target_users' => $targetUsers ? json_encode($targetUsers) : null,
        ]);

        broadcast(new NewAnnouncementEvent($announcement))->toOthers();

        return back()->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    // =================== USER ===================
    public function showToUsers()
    {
        $userId = auth()->id();

        // Tampilkan pengumuman yang ditujukan ke semua (target_users null)
        // ATAU yang target_users-nya mengandung ID user ini
        $announcements = Announcement::latest()
            ->get()
            ->filter(function ($a) use ($userId) {
                if (is_null($a->target_users)) {
                    return true; // siaran umum
                }
                $targets = is_array($a->target_users)
                    ? $a->target_users
                    : json_decode($a->target_users, true);

                return in_array($userId, (array) $targets);
            })
            ->values();

        return view('pengumuman.user', compact('announcements'));
    }

    // =================== MANAGER ===================
    public function indexManager()
    {
        $announcements = Announcement::latest()->get();
        $pegawaiList   = User::whereNotIn('role', ['admin', 'manager'])
                             ->orderBy('name')
                             ->get();

        return view('pengumuman.manager', compact('announcements', 'pegawaiList'));
    }

    public function storeManager(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'target_type'  => 'required|in:all,specific',
            'target_users' => 'required_if:target_type,specific|array|min:1',
            'target_users.*' => 'exists:users,id',
        ], [
            'target_users.required_if' => 'Pilih minimal satu pegawai yang dituju.',
        ]);

        $targetUsers = $request->target_type === 'specific'
            ? $request->target_users
            : null;

        $announcement = Announcement::create([
            'judul'        => $request->judul,
            'isi'          => $request->isi,
            'created_by'   => auth()->id(),
            'target_users' => $targetUsers ? json_encode($targetUsers) : null,
        ]);

        broadcast(new NewAnnouncementEvent($announcement))->toOthers();

        return back()->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function destroyManager($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}