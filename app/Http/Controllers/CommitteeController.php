<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Committee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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
            $committees = $admin->committees()->get();
        }
        
        return view('pages.committees', compact('committees'));
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
        ->where('is_active', 1)
        ->where('idAdmins', $admin->idAdmins)
        ->select('c.*')
        ->get();
        
        return view('pages.profile', compact('committees'));
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
