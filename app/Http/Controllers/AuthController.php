<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Auto-seed admin user if not present in DB
        if ($credentials['email'] === 'admin@gmail.com' && $credentials['password'] === '12345678') {
            try {
                $user = User::firstOrCreate(
                    ['email' => 'admin@gmail.com'],
                    [
                        'name' => 'Admin User',
                        'password' => Hash::make('12345678'),
                    ]
                );
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard'))->with('success', 'Welcome back, Admin!');
            } catch (\Throwable $e) {
                // If DB issue, simulate session login gracefully
                session(['authenticated' => true, 'user_name' => 'Admin User', 'user_email' => 'admin@gmail.com']);

                return redirect()->route('dashboard')->with('success', 'Welcome back, Admin!');
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
