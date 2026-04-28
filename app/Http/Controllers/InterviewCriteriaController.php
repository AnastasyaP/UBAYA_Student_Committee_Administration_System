<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AHPCriteria;

class InterviewCriteriaController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idCommittee = getCurrentCommitteeId($request);

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

                        ->where('ld.idCommittees', $idCommittee)
                        ->select(
                            'd.name as division',
                            'd.idDivisions as idDivision',
                            'ic.name as name',
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
    public function create($idDivision, Request $request)
    {
        
        if(!manageDivision($idDivision, $request)){
            return redirect()->back()->with('warning', 
                'Anda hanya dapat mengelola divisi Anda sendiri.');
        }

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
        $idCommittee = getCurrentCommitteeId($request);

        $request->validate([
            'ahp_criteria' => 'required|string|max:45',
            'name' => 'required|string|max:500',
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
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $ahpID = $newAHP->idAHPCriterias;
            }
        }
        
        // batesin 1 divisi di masing2 committee max 5 ahp criteria biar pairwisenya nga kebanyakan
        $mapping = DB::table('tListDivisionAHPCriterias')
            ->where('idDivisions', $request->idDivision)
            ->where('idCommittees', $idCommittee)
            ->where('idAHPCriterias', $ahpID)
            ->first();

        if(!$mapping){
            $ahpCount = DB::table('tListDivisionAHPCriterias')
                ->where('idDivisions', $request->idDivision)
                ->where('idCommittees', $idCommittee)
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
                    'idCommittees'   => $idCommittee,
                    'idAHPCriterias' => $ahpID,
                    'average_weight' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        } else {
            // reuse existing mapping
            $ListDivisionAHPCriteriasID = $mapping->idListDivisionAHPCriterias;
        }


        $InterviewCriteriasID = DB::table('tInterviewCriterias')
        ->insertGetId([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tInterviewDivisionAHPCriterias')
        ->insert([
            'idInterviewCriterias' => $InterviewCriteriasID,
            'idListDivisionAHPCriterias' => $ListDivisionAHPCriteriasID
        ]);

        if(session()->has('idCommittee')){
            return redirect()->route('members.intvcriteria')->with('success', 'Interview criteria successfully added.');
        }else{
            return redirect()->route('intvcriteria')->with('success', 'Interview criteria successfully added.');
        }
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
