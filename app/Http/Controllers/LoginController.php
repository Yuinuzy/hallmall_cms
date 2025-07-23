<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        //Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        
        if(Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email'=> 'Email atau Password salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
