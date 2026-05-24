<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate(
            ['username' => 'required', 'password' => 'required'],
            ['username.required' => 'Username wajib diisi', 'password.required' => 'Password wajib diisi']
        );

        $credentials = ['username' => $request->username, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return redirect()->route('dashboard')->with('swal', [
                'icon' => 'success', 'title' => 'Login berhasil', 'text' => 'Selamat datang ' . ($user->nama ?? $user->username)
            ]);
        }

        return back()->with('swal', ['icon' => 'error', 'title' => 'Login gagal', 'text' => 'Username atau password salah'])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('swal', ['icon' => 'success', 'title' => 'Logout berhasil', 'text' => 'Anda telah keluar dari sistem']);
    }
}