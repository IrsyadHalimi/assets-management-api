<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            Log::info('User logged in (web)', ['email' => $credentials['email']]);
            return redirect()->intended('/');
        }

        Log::warning('Login failed (web)', ['email' => $credentials['email']]);
        return back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        Log::info('User logged out (web)', ['user_id' => optional($user)->id, 'email' => optional($user)->email]);

        Auth::logout();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('Registration validation failed (web)', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Log::info('New user registered (web)', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
