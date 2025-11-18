<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/orders'); // setelah login ke dashboard admin
        }

        return back()->withErrors([
            'login' => 'Email atau password salah!'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
