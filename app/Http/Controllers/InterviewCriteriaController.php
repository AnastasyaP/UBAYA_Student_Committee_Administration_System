<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AHPCriteria;

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
                            'd.idDivisions as idDivision',
                            'ic.question as question',
                            'ic.max_score as max_score',
                            'ac.name as ahpCriteria'
                        )
                        ->orderBy('d.idDivisions')
                        ->get()
                        ->groupBy('idDivision');

                        // dd($intvCriteria);

        return view('pages.intvcriteria.intvcriteria', compact('intvCriteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idDivision)
    {
        $masterAHPcriteria = AHPCriteria::all();
        $division = DB::table('tDivisions')
                        ->where('idDivisions', $idDivision)
                        ->first();


        return view('pages.intvcriteria.add-intvcriteria', compact('masterAHPcriteria', 'division'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        $request->validate([
            'ahp_criteria' => 'required|string|max:45',
            'question' => 'required|string|max:500',
            'max_score' => 'required|integer',
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $ahp = $request->ahp_criteria; // ambil nama dari text input
        $master_ahp = $request->master_ahp; //ambil nama dari combobox

        // klo comboboxnya ada valuenya pake yg dr combobox klo nga buat divisi baru (tapi di cek dulu ada yg sama nga namanya)
        if ($master_ahp) {
            $ahpID = $master_ahp;
        } else {
            $existingAHP = AHPCriteria::where('name', $ahp)->first();
            if ($existingAHP) { 
                $ahpID = $existingAHP->idAHPCriterias;
            } else {
                $newAHP = AHPCriteria::create([
                    'name' => $request->ahp_criteria,
                    'idDivisions' => $request->idDivision
                ]);
                $ahpID = $newAHP->idAHPCriterias;
            }
        }
        
        // batesin 1 divisi di masing2 committee max 5 ahp criteria biar pairwisenya nga kebanyakan
        $mapping = DB::table('tListDivisionAHPCriterias')
            ->where('idDivisions', $request->idDivision)
            ->where('idCommittees', $this->committee->idCommittees)
            ->where('idAHPCriterias', $ahpID)
            ->first();

        if(!$mapping){
            $ahpCount = DB::table('tListDivisionAHPCriterias')
                ->where('idDivisions', $request->idDivision)
                ->where('idCommittees', $this->committee->idCommittees)
                ->count();

            if($ahpCount >= 5){
                DB::rollBack();
                return redirect()->back()->with('warning',
                    'Satu divisi maksimal hanya boleh memiliki 5 AHP Criteria.'
                );
            }

            // create new mapping
            $ListDivisionAHPCriteriasID = DB::table('tListDivisionAHPCriterias')
                ->insertGetId([
                    'idDivisions'    => $request->idDivision,
                    'idCommittees'   => $this->committee->idCommittees,
                    'idAHPCriterias' => $ahpID,
                    'average_weight' => 0,
                ]);
        } else {
            // reuse existing mapping
            $ListDivisionAHPCriteriasID = $mapping->idListDivisionAHPCriterias;
        }


        $InterviewCriteriasID = DB::table('tInterviewCriterias')
        ->insertGetId([
            'question' => $request->question,
            'max_score' => $request->max_score,
        ]);

        DB::table('tInterviewDivisionAHPCriterias')
        ->insert([
            'idInterviewCriterias' => $InterviewCriteriasID,
            'idListDivisionAHPCriterias' => $ListDivisionAHPCriteriasID
        ]);

        return redirect()->route('intvcriteria')->with('success', 'Interview criteria successfully added.');
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
