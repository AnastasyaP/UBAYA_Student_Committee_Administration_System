<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InterviewCriteriaController extends Controller
{
    protected $admin;
    protected $committee;

    public function __construct(){
        $this->admin = null;
        $this->committee = null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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

        $intvCriteria = DB::table('tListDivisions as ld')
                        ->join('tDivisions as d', 'ld.idDivisions', '=', 'd.idDivisions')
                        // mapping ke AHP
                        ->leftJoin('tListDivisionAHPCriterias as lc', function($join){
                            $join->on('ld.idDivisions', '=', 'lc.idDivisions');
                            $join->on('ld.idCommittees', '=', 'lc.idCommittees');
                        })
                        ->leftJoin('tAHPCriterias as ac', 'lc.idAHPCriterias', '=', 'ac.idAHPCriterias')
                        // mapping ke interview
                        ->leftJoin('tInterviewDivisionAHPCriterias as dc',
                            'lc.idListDivisionAHPCriterias', '=', 'dc.idListDivisionAHPCriterias')

                        ->leftJoin('tInterviewCriterias as ic',
                            'dc.idInterviewCriterias', '=', 'ic.idInterviewCriterias')

                        ->where('ld.idCommittees', $this->committee->idCommittees)
                        ->select(
                            'd.name as division',
                            'ic.question as question',
                            'ic.max_score as max_score',
                            'ac.name as ahpCriteria'
                        )
                        ->orderBy('d.idDivisions')
                        ->get()
                        ->groupBy('division');

                        // dd($intvCriteria);

        return view('pages.intvcriteria.intvcriteria', compact('intvCriteria'));
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
