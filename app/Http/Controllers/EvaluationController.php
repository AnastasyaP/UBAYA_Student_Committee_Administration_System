<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $target = 'user')
    {
        $idCommittee = getCurrentCommitteeId($request);

        $criterias = DB::table('tEvaluationCriterias as ec')
                        ->join('tEvaluationCriteriaScopes as es', 'es.idEvaluationCriterias', 'ec.idEvaluationCriterias')
                        ->leftJoin('tDivisions as d', 'es.idDivisions', 'd.idDivisions')
                        ->where('es.idCommittees', $idCommittee)
                        ->select([
                            'ec.name as name',
                            'ec.description as description',
                            'ec.target_type as target_type',
                            'es.idDivisions as division',
                            'd.name as division_name',
                        ])
                        ->when($target, function($query) use ($target) {
                            return $query->where('ec.target_type', $target);
                        })
                        ->get();
                        // dd($criterias);

        $masterTarget = [
            'user' => 'User',
            'committee' => 'Kepanitiaan',
            'division' => 'Divisi'
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'criterias' => $criterias
            ]);
        }

        return view('pages.evaluation.evalcriteria', compact('criterias', 'masterTarget', 'target'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $criterias = DB::table('tEvaluationCriterias')
                        ->get();

        $idCommittee = getCurrentCommitteeId($request);

        $divisions = DB::table('tDivisions as d')
                    ->join('tListDivisions as ld', function($join) use($idCommittee){
                        $join->on('d.idDivisions', '=', 'ld.idDivisions')
                            ->where('ld.idCommittees', $idCommittee);
                    })
                    ->get();

         $masterTarget = [
            'user' => 'User',
            'committee' => 'Kepanitiaan',
            'division' => 'Divisi'
        ];

        return view('pages.evaluation.add-evalcriteria', compact('criterias', 'divisions', 'masterTarget'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'eval_criteria' => 'required',
            'description' => 'required',
            'target_eval' => 'required',
            'target_divisi' => 'required_if:target_eval,division',
        ]);

        $idCommittee = getCurrentCommitteeId($request);

        $criteria = $request->eval_criteria;
        $masterCriteria = $request->master_eval; // cmb

        if($masterCriteria){
            $idCriteria = $masterCriteria;
        }else{
            $exists = DB::table('tEvaluationCriterias')
                        ->where('name', $criteria)
                        ->first();
            if($exists){
                $idCriteria = $exists->idEvaluationCriterias;
            }else{
                $idCriteria = DB::table('tEvaluationCriterias')
                        ->insertGetId([
                            'name' => $request->eval_criteria,
                            'description' => $request->description,
                            'target_type' => $request->target_eval
                        ]);
            }
        }

        $idDivision = $request->target_eval === 'division'
            ? $request->target_divisi
            : null;

        $existsScope = DB::table('tEvaluationCriteriaScopes')
            ->where('idEvaluationCriterias', $idCriteria)
            ->where('idCommittees', $idCommittee)
            ->where('idDivisions', $idDivision)
            ->exists();

        if(!$existsScope){
            DB::table('tEvaluationCriteriaScopes')->insert([
                'idEvaluationCriterias' => $idCriteria,
                'idCommittees' => $idCommittee,
                'idDivisions' => $idDivision
            ]);
        }

        return redirect()->route('evalcriteria')->with('success', 'Berhasil menyimpan kriteria evaluasi baru');
         
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
