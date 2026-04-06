<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Find user by username
        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            
            // Redirect based on role
            $role = strtolower($user->role ?? '');
            
            if (in_array($role, ['admin', 'administrator', 'pengelola', 'manager', 'superadmin'])) {
                return redirect()->route('admin.dashboard');
            } elseif (in_array($role, ['petugas', 'staff'])) {
                return redirect()->route('petugas.dashboard');
            } elseif (in_array($role, ['peminjam', 'borrower', 'user', 'pengguna'])) {
                return redirect()->route('peminjam.tools');
            } else {
                // For other roles, redirect to appropriate dashboard
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}