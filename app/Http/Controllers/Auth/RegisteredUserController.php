<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'no_hp' => [
                'required',
                'string',
                'max:20',
                'unique:users,no_hp'
            ],

            // Tambahan role mandor
            'role' => [
                'required',
                'string',
                'in:user,security,cleaning,kantoran,manager,admin,mandor'
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect berdasarkan role
        return $this->redirectBasedOnRole($user->role);
    }

    /**
     * Redirect user based on role.
     */
    private function redirectBasedOnRole(string $role): RedirectResponse
    {
        return match ($role) {

            'admin' => redirect('admin/dashboard'),

            'manager' => redirect('manager/dashboard'),

            'security' => redirect('security/dashboard'),

            'cleaning' => redirect('cleaning/dashboard'),

            'kantoran' => redirect('kantoran/dashboard'),

            // Role baru
            'mandor' => redirect('mandor/dashboard'),

            default => redirect()->intended(route('dashboard')),
        };
    }
}