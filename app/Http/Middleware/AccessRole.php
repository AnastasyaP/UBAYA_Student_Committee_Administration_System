<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccessRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isAllowed = DB::table('tRegistrations')
                        ->where('idCommittees', $request->idCommittee)
                        ->where('isUsers', Auth::id())
                        ->whereIn('position', ['BPH-SC', 'Koordinator', 'Wakil Koordinator'])
                        ->where('status', 'diterima')
                        ->exists();

        if(!$isAllowed){
            abort(403, 'Akses Ditolak');
        }
        
        return $next($request);
    }
}
