<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_user'  => 'required|string|max:255',
            'email_user' => 'required|email|unique:users,email_user',
            'pass_user'  => 'required|min:8|confirmed',
            'role_user'  => 'required|in:karyawan,manager,admin,admin_trans,driver',
        ], [
            'nama_user.required'   => 'Nama wajib diisi.',
            'email_user.required'  => 'Email wajib diisi.',
            'email_user.email'     => 'Format email tidak valid.',
            'email_user.unique'    => 'Email sudah terdaftar.',
            'pass_user.required'   => 'Password wajib diisi.',
            'pass_user.min'        => 'Password minimal 8 karakter.',
            'pass_user.confirmed'  => 'Konfirmasi password tidak cocok.',
            'role_user.required'   => 'Role wajib dipilih.',
            'role_user.in'         => 'Role tidak valid.',
        ]);

        $user = User::create([
            'nama_user'  => $request->nama_user,
            'email_user' => $request->email_user,
            'pass_user'  => Hash::make($request->pass_user),
            'role_user'  => $request->role_user,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}