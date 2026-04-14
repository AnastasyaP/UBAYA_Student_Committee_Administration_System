<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadCommittee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->back()->with('warning', 'No access');
        }

        if (!session()->has('displayed_committee')) {
            if($request->is('committees*')){
                return $next($request);
            }
            return redirect('/choose-committees')->with('warning', 'Pilih kepanitiaan dulu');
        }

        $committee = DB::table('tCommittees')
            ->where('idCommittees', session('displayed_committee'))
            ->first();

        if(!$committee || $committee->is_active == 0){
            $otherCommittee = DB::table('tCommittees')
            ->where('admin', Auth::id())
            ->where('is_active', 1)
            ->orderBy('idCommittees', 'desc')
            ->first();

            if($otherCommittee){
                session(['displayed_committee' => $otherCommittee->idCommiittees]);

                return redirect()->back()->with('warning', 'Kepanitiaan sebelumnya sudah berakhir, tampilan dialihkan ke kepanitian yang sedang aktif');
            }
            return redirect('/choose-committees')->with('warning', 'Tidak ada kepanitiaan yang aktif');
        }

        // share ke semua view
        view()->share('displayed_committee', $committee);

        // bisa juga inject ke request kalau mau
        $request->attributes->set('displayed_committee', $committee);

        return $next($request);
    }
}
