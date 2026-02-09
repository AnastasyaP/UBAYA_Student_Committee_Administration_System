<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AHPCalculationController extends Controller
{
    protected $admin;
    protected $committee;

    public function __construct(){
        $this->admin = null;
        $this->committee = null;
    }

    function init(){
        $user = Auth::user();
        if($user->role === 'admin'){
            $this->admin = $user;
        }else{
            return redirect()->back()->with('warning', "this account doesn't have an authority");
        }

        $this->committee = DB::table('tUsers as u')
                        ->join('tCommittees as c', 'u.idUsers', 'c.admin')
                        ->where('c.admin', $this->admin->idUsers)
                        ->where('is_active', 1)
                        ->first();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->init();

        $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $this->committee->idCommittees)
                            ->get();

        // $default = $masterDivision->first()->idDivisions;

        $default = 3;
        $criterias = DB::table('tListDivisionAHPCriterias as lc')
                        ->join('tAHPCriterias as ac', 'lc.idAHPCriterias', 'ac.idAHPCriterias')
                        ->where('lc.idDivisions', $default)
                        ->where('lc.idCommittees', $this->committee->idCommittees)
                        ->get();
        // dd($criterias);
        $pairwise = [];
        for($i = 0; $i < count($criterias); $i++){
            for($j = $i+1; $j < count($criterias); $j++){
                $pairwise[] = [
                    'c1'=> $criterias[$i],
                    'c2'=> $criterias[$j]
                ];
            }
        }

        return view('pages.ahpcalculation.ahpcalculation', compact('masterDivision', 'default', 'criterias', 'pairwise'));
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
