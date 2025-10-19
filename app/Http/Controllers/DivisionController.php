<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Auth::user();
        
        $divisions = DB::table('tDivisions as d')
        ->join('tListDivisions as ld', 'd.idDivisions', '=', 'ld.idDivisions')
        ->join('tCommittees as c', 'ld.idCommittees', '=', 'c.idCommittees')
        ->where('c.idAdmins', $admin->idAdmins,)
        ->where('c.is_active', 1)
        ->select('d.name as name', 'ld.is_open as status', 'ld.description as description', 'ld.picture as picture')
        ->get();

        return view('pages.division.divisions', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.division.add-divisions');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        Division::create($input);
        return redirect()->route('divisions')->with('success', 'Division added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
