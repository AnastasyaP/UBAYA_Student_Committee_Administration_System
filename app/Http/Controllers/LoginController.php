<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        // cek input email atau username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) 
                        ? 'email' 
                        : 'username';

        // cari user
        $user = User::where($loginType, $request->login)->first();

        // jika user ada tapi tidak aktif
        if($user && $user->is_active == 0){
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi admin untuk keterangan lebih lanjut.',
            ])->onlyInput('login');
        }

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            
            $role = Auth::user()->role;

            if(isSuperAdmin()){
                return redirect('/dashboard-superadmin');
            }

            if($role === 'admin'){
                return redirect('/dashboard');
            } 
            
            if($role === "mahasiswa"){
                return redirect('/home');
            }
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
            'email' => 'Email/username atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // if(Auth::guard('admin')->check()){
        //     Auth::guard('admin')->logout();
        // } elseif(Auth::guard('web')->check()){
        //     Auth::guard('web')->logout();
        // }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        return redirect('/login');
    }
}
