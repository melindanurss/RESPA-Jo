<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('login2');
    }

    public function login(Request $request)
    {
        $request->validate(['username' => 'required', 'password' => 'required']);
        $user = User::where('username', $request->username)->first();
        if (!$user) return back()->withErrors(['username' => 'Username tidak ditemukan'])->onlyInput('username');
        
        $email = $request->username . '@test.com';
        if (Auth::attempt(['email' => $email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
        return back()->withErrors(['username' => 'Password salah'])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    
    public function store(Request $request){
        $request->validate(['Nama' => 'required', 'Username' => 'required', 'Email' => 'required|email', 'Password' => 'required', 'Role' => 'required']);
        User::create(['nama' => $request->Nama, 'username' => $request->Username, 'email' => $request->Email, 'password' => Hash::make($request->Password), 'role' => $request->Role]);
        return response()->json(['Status' => 'Sukses', 'Message' => 'Data berhasil disimpan']);
    }
}