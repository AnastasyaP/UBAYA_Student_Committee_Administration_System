<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $admin = null;
        if($user->role === 'admin'){
            $admin = $user;
        } else {
            return redirect()->route('dashboard')->with('warning', 'You are not an administrator!');
        }

        $registrations = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where('c.admin', $admin->idUsers)
        ->whereColumn('r.idCommittees', 'c.idCommittees')
        ->where('c.is_active', 1)
        ->select(
            DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
            'u.email as email',
            'm.nrp as nrp',
            'm.cv as cv',
            'm.portofolio as portofolio',
            'r.status as status',
            'r.idDivisions as idDivision',
            'd.name as division',
            'r.idRegistrations as idRegis',
            'r.idUsers as idMahasiswa',
        )
        ->get();

        return view('pages.registration.registrations', compact('registrations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($idRegis)
    {
        $user = Auth::user();
        $admin = null;
        if($user->role === 'admin'){
            $admin = $user;
        } else {
            return redirect()->route('dashboard')->with('warning', 'You are not an administrator!');
        }

        $registration = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where('c.admin', $admin->idUsers)
        ->where('r.idRegistrations', $idRegis)
        ->whereColumn('r.idCommittees', 'c.idCommittees')
        ->select(
            DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
            'u.email as email',
            'm.nrp as nrp',
            'm.cv as cv',
            'm.portofolio as portofolio',
            'r.status as status',
            'r.idDivisions as idDivision',
            'r.percentage as percentage',
            'd.name as division',
        )
        ->first();

        return view('pages.registration.view-registrations', compact('registration'));
    }

    public function accept($idRegis){
        $mhs = DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->select('*')
                ->first();
        if($mhs->status === "pending"){
            DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->update(['status'=> 'accepted']);
        } elseif($mhs->status === "accepted"){
            return redirect()->back()->with('warning', 'This user is already accepted');
        } else{
            return redirect()->back()->with('warning', 'This user is already rejected');
        }
        return redirect()->route('registration')->with('success', 'Status updated!');
    }

        public function reject($idRegis){
        $mhs = DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->select('*')
                ->first();
        if($mhs->status === "pending"){
            DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->update(['status'=> 'rejected']);
        } elseif($mhs->status === "accepted"){
            return redirect()->back()->with('warning', 'This user is already accepted');
        } else{
            return redirect()->back()->with('warning', 'This user is already rejected');
        }
        return redirect()->route('registration')->with('success', 'Status updated!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
