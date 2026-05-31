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

        // Daftar role yang tersedia (sesuaikan dengan role di sistem Anda)
        $roleList = ['user', 'mandor', 'security', 'cleaning', 'kantoran'];

        return view('pengumuman.admin', compact('announcements', 'pegawaiList', 'roleList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'target_type'  => 'required|in:all,specific,role',
            'target_users' => 'required_if:target_type,specific|array|min:1',
            'target_users.*' => 'exists:users,id',
            'target_roles' => 'required_if:target_type,role|array|min:1',
            'target_roles.*' => 'string',
        ], [
            'target_users.required_if' => 'Pilih minimal satu pegawai yang dituju.',
            'target_roles.required_if' => 'Pilih minimal satu role yang dituju.',
        ]);

        $targetUsers = null;
        $targetRoles = null;

        if ($request->target_type === 'specific') {
            $targetUsers = $request->target_users;
        } elseif ($request->target_type === 'role') {
            $targetRoles = $request->target_roles;
        }

        $announcement = Announcement::create([
            'judul'        => $request->judul,
            'isi'          => $request->isi,
            'created_by'   => auth()->id(),
            'target_users' => $targetUsers,
            'target_roles' => $targetRoles,
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
        $userId   = auth()->id();
        $userRole = auth()->user()->role;

        $announcements = Announcement::latest()
            ->get()
            ->filter(function ($a) use ($userId, $userRole) {
                // Prioritas 1: target berdasarkan ID pegawai spesifik
                if (!is_null($a->target_users)) {
                    return in_array($userId, $a->target_users);
                }

                // Prioritas 2: target berdasarkan role
                if (!is_null($a->target_roles)) {
                    return in_array($userRole, $a->target_roles);
                }

                // Default: siaran umum
                return true;
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

        $roleList = ['user', 'mandor', 'security', 'cleaning', 'kantoran'];

        return view('pengumuman.manager', compact('announcements', 'pegawaiList', 'roleList'));
    }

    public function storeManager(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'target_type'  => 'required|in:all,specific,role',
            'target_users' => 'required_if:target_type,specific|array|min:1',
            'target_users.*' => 'exists:users,id',
            'target_roles' => 'required_if:target_type,role|array|min:1',
            'target_roles.*' => 'string',
        ], [
            'target_users.required_if' => 'Pilih minimal satu pegawai yang dituju.',
            'target_roles.required_if' => 'Pilih minimal satu role yang dituju.',
        ]);

        $targetUsers = null;
        $targetRoles = null;

        if ($request->target_type === 'specific') {
            $targetUsers = $request->target_users;
        } elseif ($request->target_type === 'role') {
            $targetRoles = $request->target_roles;
        }

        $announcement = Announcement::create([
            'judul'        => $request->judul,
            'isi'          => $request->isi,
            'created_by'   => auth()->id(),
            'target_users' => $targetUsers,
            'target_roles' => $targetRoles,
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