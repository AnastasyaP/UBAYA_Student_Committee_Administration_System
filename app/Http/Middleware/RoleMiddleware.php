<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if(!auth()->check()){
            return redirect('/login');
        }

        $user = auth()->user();

        // cek apakah akun masih aktif
        if($user->is_active == 0){

            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->withErrors([
                'error' => 'Akun Anda telah dinonaktifkan. Silakan hubungi admin untuk keterangan lebih lanjut.'
            ]);
        }

        //super admin
        if($role === 'superadmin'){
            if($user->role !== 'admin'){
                abort(403);
            }

            $isSuper = DB::table('tAdmins')
                ->where('idUsers', $user->idUsers)
                ->where('is_superAdmin', 1)
                ->exists();

            if(!$isSuper){
                abort(403);
            }

            return $next($request);
        }

        // user biasa (selain super admin)
        if($user->role !== $role){
            abort(403); // lgsg nge stop proses, dia returnnya error forbidden
            // return redirect('/login')->withErrors('Unauthorized');
        }
        return $next($request);
    }
}
