<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->username;

        $committees = DB::table('tCommittees')
                    ->where('is_active', 1)
                    ->get();

        return view('pages.landingpage.index', compact('committees', 'user'));
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
    public function show($idCommittee)
    {
        // dd($idCommittee);
        $committee = DB::table('tCommittees')
                    ->where('idCommittees', $idCommittee)
                    ->select('*')
                    ->first();

        $divisions = DB::table('tListDivisions as ld')
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('ld.idCommittees', $idCommittee)
                    ->where('ld.is_open', 1)
                    ->select('d.name as name', 'ld.description as description', 'ld.picture as picture')
                    ->get();
        return view('pages.landingpage.detail-committee', compact('committee', 'divisions'));
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
