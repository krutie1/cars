<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'phone_number' => ['required', 'regex:/^8\d{10}$/'],
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('client');
        }

        return back()->withErrors([
            'phone_number' => 'Неправильный номер телефона или пароль',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
