<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::guard('admin')->attempt($credentials)){
            return redirect()->intended('/dashboard');
        }
        if(Auth::guard('web')->attempt($credentials)){
            return redirect()->intended('/home');
        }
        // $user = Admin::where('emailAdmins',$request->email)->first();
        // dd($user);

        // if($user && Hash::check($request->password, $user->password)){
        //     // dd($user, Hash::check($request->password, $user->password));
        //     Auth::login($user);
        //     $request->session()->regenerate();
        //     return redirect()->intended('dashboard');
        // }
        // tanpa hashing 
        // if($user){
        //     if($user->password === $request->password){
        //         Auth::login($user);
        //         $request->session()->regenerate();
        //         return redirect()->intended('dashboard');
        //     }
        // }

        // bawaan template argon
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     $request->session()->regenerate();

        //     return redirect()->intended('dashboard');
        // }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        // Auth::logout();
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
        } elseif(Auth::guard('web')->check()){
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
