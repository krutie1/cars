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
//            $request->session()->put('last_login', now()->subDay()->format('Y-m-d H:i:s'));
            $request->session()->put('last_login', now()->format('Y-m-d H:i:s'));

//            file_put_contents('temp.txt', $request->session()->get('last_login') . PHP_EOL, FILE_APPEND);


            return redirect('/clients');
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
