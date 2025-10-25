<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Committee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizerUnit;


class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Auth::user(); // ngambil admin yg login
        if($admin->is_superAdmin === 1){
            $committees = Committee::all();
        }else{
            $committees = DB::table('tCommittees as c')
                ->join('tCommitteeOrganizers as co', 'c.idCommittees', 'co.idCommittees')
                ->join('tOrganizerUnits as o', 'co.idOrganizerUnits', 'o.idOrganizerUnits')
                ->where('idAdmins', $admin->idAdmins)
                ->select('c.*', DB::raw("'". $admin->emailAdmins . "'as email"), 'co.idOrganizerUnits as idOrganizerUnits', 'o.name as organizerName')
                ->get();
            
            $activeCommittee = false;
            $exists = Committee::where('idAdmins', $admin->idAdmins)
                        ->where('is_active', 1)
                        ->exists();
            if($exists){
                $activeCommittee = true;
            }
        }
        
        return view('pages.committees', compact('committees', 'activeCommittee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.add-committees');
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
    public function show()
    {
        $admin = Auth::user(); // ngambil admin yg login
        
        // $committees = Committee::all();
        $committees = DB::table('tCommittees as c')
        ->join('tCommitteeOrganizers as co', 'c.idCommittees', 'co.idCommittees')
        ->join('tOrganizerUnits as o', 'co.idOrganizerUnits', 'o.idOrganizerUnits')
        ->where('is_active', 1)
        ->where('idAdmins', $admin->idAdmins)
        ->select('c.*', DB::raw("'". $admin->emailAdmins . "'as email"), 'co.idOrganizerUnits as idOrganizerUnits', 'o.name as organizerName')
        ->get();
        
        $masterOrganizer = OrganizerUnit::all();

        return view('pages.profile', compact('committees', 'masterOrganizer'));
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
