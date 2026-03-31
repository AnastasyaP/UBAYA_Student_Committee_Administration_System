<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InterviewScoringController extends Controller
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
    public function index(Request $request)
    {
        $this->init();

        //kirim id pendaftar
        //id evaluator ambil dari yg lg login
        //kirim id divisi
        $idMahasiswa = $request->idMahasiswa;
        $idDivision = $request->idDivision;
        $idEvaluator = Auth::user();

        $criterias = DB::table('tListDivisionAHPCriterias as la')
                    ->join('tInterviewDivisionAHPCriterias as ia', 'la.idListDivisionAHPCriterias', 'ia.idListDivisionAHPCriterias')
                    ->join('tInterviewCriterias as ic', 'ia.idInterviewCriterias', 'ic.idInterviewCriterias')
                    ->where('la.idCommittees', $this->committee->idCommittees)
                    ->where('la.idDivisions', $idDivision)
                    ->select(
                        'ic.name as kriteria'
                    )
                    ->get();

        $divisionName = DB::table('tDivisions')
                        ->where('idDivisions', $idDivision)
                        ->first();

        $mahasiswa = DB::table('tUsers')
                        ->where('idUsers', $idMahasiswa)
                        ->select(
                            'idUsers as idUser',
                            DB::raw("concat(firstname, ' ', lastname) as name"),
                            'email as email'
                        )
                        ->first();

        return view('pages.intvscoring.intvscoring', compact('criterias', 'divisionName', 'mahasiswa'));

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
