<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('getCurrentCommitteeId')) {
    function getCurrentCommitteeId($request = null)
    {
        // mode koor (session)
        if (session()->has('idCommittee')) {
            return session('idCommittee');
        }

        // mode admin (request)
        if ($request && $request->get('displayed_committee')) {
            return $request->get('displayed_committee')->idCommittees;
        }

        return null;
    }
}

 if (!function_exists('routeForMember')) {
            function routeForMember($adminRoute, $memberRoute)
            {
                // kalau sedang di mode koor (punya session idCommittee)
                if (session()->has('idCommittee')) {
                    return $memberRoute;
                }

                // default admin
                return $adminRoute;
            }
}

if (!function_exists('manageDivision')) {
    function manageDivision($idDivision, $request = null)
    {
        $idCommittee = getCurrentCommitteeId($request);

        if(!$idCommittee){
            return false;
        }

        $user = Auth::user();

        // 🔥 ADMIN = bebas
        if($user->role === 'admin'){
            return true;
        }

        // 🔥 KOOR = harus divisi yang sama
        return DB::table('tRegistrations')
            ->where('idCommittees', $idCommittee)
            ->where('idUsers', $user->idUsers)
            ->where('idDivisions', $idDivision) // 🔥 kunci utama
            ->whereIn('position', ['Koordinator', 'Wakil Koordinator', 'BPH-SC'])
            ->where('status', 'diterima')
            ->exists();
    }
}