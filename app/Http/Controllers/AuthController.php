<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Email atau password tidak valid.');
        }

        // Sanitize input
        $credentials = [
            'email' => strtolower(trim($request->email)),
            'password' => $request->password
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            } elseif ($user->isStaff()) {
                return redirect()->route('staff.dashboard')->with('success', 'Welcome Staff!');
            } else {
                return redirect()->route('user.dashboard')->with('success', 'Welcome!');
            }
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput()
            ->with('error', 'Login gagal. Periksa kembali credentials Anda.');
    }

    public function register(Request $request)
    {
        $currentYear = date('Y');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users|regex:/^[a-zA-Z0-9._%+-]+@students\.um\.ac\.id$/',
            'password' => 'required|min:8|max:255|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'password_confirmation' => 'required',
            'nim' => 'required|string|min:12|max:20|unique:users',
            'phone' => 'required|string|min:11|max:15',
            'fakultas' => 'required|string|min:2|max:255',
            'jurusan' => 'required|string|min:2|max:255',
            'angkatan' => 'required|integer|digits:4|min:2000|max:' . ($currentYear + 1),
        ], [
            'email.unique' => 'Email sudah terdaftar. Gunakan email lain.',
            'email.regex' => 'Email harus menggunakan domain @students.um.ac.id',
            'nim.unique' => 'NIM sudah terdaftar. Gunakan NIM lain.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*?&)',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa form Anda.')
                ->with('register_errors', true); // TAMBAH INI
        }

        // Sanitize data sebelum disimpan
        $user = User::create([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'phone' => preg_replace('/[^0-9+]/', '', $request->phone),
            'nim' => trim($request->nim),
            'fakultas' => trim($request->fakultas),
            'jurusan' => trim($request->jurusan),
            'angkatan' => (int) $request->angkatan,
            'role' => 'user',
            'email_verified_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di SI-UKM.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}