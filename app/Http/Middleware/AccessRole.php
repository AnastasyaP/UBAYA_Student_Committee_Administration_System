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
        $idCommittee = session('idCommittee');

        $isAllowed = DB::table('tRegistrations')
                        ->where('idCommittees', $idCommittee)
                        ->where('idUsers', Auth::id())
                        ->whereIn('position', ['BPH-SC', 'Koordinator', 'Wakil Koordinator'])
                        ->where('status', 'diterima')
                        ->exists();

        if(!$isAllowed){
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }
        
        return $next($request);
    }
}
