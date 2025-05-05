<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Hapus autentikasi
        $request->session()->invalidate(); // Hapus semua session
        $request->session()->regenerateToken(); // Regenerate CSRF token baru
    
        return redirect('/login')->with('logged_out', true);
    }

    public function postLog(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        // Cek kredensial pengguna
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $request->session()->regenerate(); // Hindari session fixation attack
            return redirect('/dashboard')->with('success', 'Login berhasil!');
        }
    
        // Jika login gagal
        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    
    }

    public function postReg(Request $request)
    {        // Simpan data ke database
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Jika pendaftaran berhasil
        if ($user) {
            return redirect("/login")->with('success', 'Registrasi berhasil, silakan login.');
        } else {
            return back()->with('error', 'Registrasi gagal, coba lagi.');
        }
    }
}
