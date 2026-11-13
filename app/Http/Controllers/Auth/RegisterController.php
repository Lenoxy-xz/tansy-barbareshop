<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'no_hp'    => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi!',
            'username.required'  => 'Username wajib diisi!',
            'username.unique'    => 'Username sudah digunakan!',
            'email.required'     => 'Email wajib diisi!',
            'email.email'        => 'Format email tidak valid!',
            'email.unique'       => 'Email sudah digunakan!',
            'no_hp.required'     => 'No. HP wajib diisi!',
            'password.required'  => 'Password wajib diisi!',
            'password.min'       => 'Password minimal 8 karakter!',
            'password.confirmed' => 'Konfirmasi password tidak sesuai!',
        ]);

        User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
