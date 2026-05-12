<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\AHPService;

class AHPCalculationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AHPService $ahp)
    {
        $idCommittee = getCurrentCommitteeId($request);

        $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $idCommittee)
                            ->get();

        $default = $masterDivision->first()->idDivisions;

        $criterias = DB::table('tListDivisionAHPCriterias as lc')
                        ->join('tAHPCriterias as ac', 'lc.idAHPCriterias', 'ac.idAHPCriterias')
                        ->where('lc.idDivisions', $default)
                        ->where('lc.idCommittees', $idCommittee)
                        ->get();

        $pairwiseDB = DB::table('tPairwiseComparisons')
                        ->where('idDivisions', $default)
                        ->where('idCommittees', $idCommittee)
                        ->get();

       // buat pairwise tampilan
        $pairwise = [];
        for($i = 0; $i < count($criterias); $i++){
            for($j = $i+1; $j < count($criterias); $j++){
                $weight = $pairwiseDB
                        ->where('idCriteria1', $criterias[$i]->idAHPCriterias)
                        ->where('idCriteria2', $criterias[$j]->idAHPCriterias)
                        ->first();

                $pairwise[] = [
                    'c1'=> $criterias[$i],
                    'c2'=> $criterias[$j],
                    'weight' => $weight->weight ?? 1,
                ];
            }
        }

        // default
        $result = [
            'matrix'=>[],
            'column_sum'=>[],
            'normalized'=>[],
            'priority_vector'=>[],
            'weighted_sum'=>[],
            'lambda_vector'=>[],
            'lambda_max'=>null,
            'CI'=>null,
            'CR'=>null,
            'RI'=>null,
            'is_consistent'=>null
        ];

        if($pairwiseDB->isNotEmpty()){
            $result = $ahp->calculate($criterias, $pairwiseDB);
        }

        $isUsed = DB::table('tInterviewEvaluationScores as ies')
                ->join('tInterviewCriterias as ic', 'ies.idInterviewCriterias', 'ic.idInterviewCriterias')
		        ->join('tInterviewDivisionAHPCriterias as idac', 'ic.idInterviewCriterias', 'idac.idInterviewCriterias')
		        ->join('tListDivisionAHPCriterias as ldac', 'idac.idListDivisionAHPCriterias', 'ldac.idListDivisionAHPCriterias')
                ->where('ldac.idCommittees', $idCommittee)
                ->where('ldac.idDivisions', $default)
                ->exists();

        return view('pages.ahpcalculation.ahpcalculation', compact(
            'masterDivision', 
            'default', 
            'criterias', 
            'pairwise',
            'result',
            'isUsed'
        ));
    }

    public function getCriteriasByDivision($idDivision, Request $request, AHPService $ahp){
        $idCommittee = getCurrentCommitteeId($request);

        $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $idCommittee)
                            ->get();

        $criterias = DB::table('tListDivisionAHPCriterias as lc')
                        ->join('tAHPCriterias as ac', 'lc.idAHPCriterias', 'ac.idAHPCriterias')
                        ->where('lc.idDivisions', $idDivision)
                        ->where('lc.idCommittees', $idCommittee)
                        ->get();
        
        $pairwiseDB = DB::table('tPairwiseComparisons')
                        ->where('idDivisions', $idDivision)
                        ->where('idCommittees', $idCommittee)
                        ->get();

        // buat pairwise tampilan
        $pairwise = [];
        for($i = 0; $i < count($criterias); $i++){
            for($j = $i+1; $j < count($criterias); $j++){
                $weight = $pairwiseDB
                        ->where('idCriteria1', $criterias[$i]->idAHPCriterias)
                        ->where('idCriteria2', $criterias[$j]->idAHPCriterias)
                        ->first();

                $pairwise[] = [
                    'c1'=> $criterias[$i],
                    'c2'=> $criterias[$j],
                    'weight' => $weight->weight ?? 1,
                ];
            }
        }

        // default
        $result = [
            'matrix'=>[],
            'column_sum'=>[],
            'normalized'=>[],
            'priority_vector'=>[],
            'weighted_sum'=>[],
            'lambda_vector'=>[],
            'lambda_max'=>null,
            'CI'=>null,
            'CR'=>null,
            'RI'=>null,
            'is_consistent'=>null
        ];

        if($pairwiseDB->isNotEmpty()){
            $result = $ahp->calculate($criterias, $pairwiseDB);
        }

        $isUsed = DB::table('tInterviewEvaluationScores as ies')
                ->join('tInterviewCriterias as ic', 'ies.idInterviewCriterias', 'ic.idInterviewCriterias')
		        ->join('tInterviewDivisionAHPCriterias as idac', 'ic.idInterviewCriterias', 'idac.idInterviewCriterias')
		        ->join('tListDivisionAHPCriterias as ldac', 'idac.idListDivisionAHPCriterias', 'ldac.idListDivisionAHPCriterias')
                ->where('ldac.idCommittees', $idCommittee)
                ->where('ldac.idDivisions', $idDivision)
                ->exists();

        return response()->json([
            'criterias' => $criterias,
            'pairwise' => $pairwise,
            'masterDivision' => $masterDivision,
            'matrix' => $result['matrix'],
            'column_sum' => $result['column_sum'],
            'normalized' => $result['normalized'],
            'priority_vector' => $result['priority_vector'],
            'weighted_sum' => $result['weighted_sum'],
            'lambda_vector' => $result['lambda_vector'],
            'lambda_max' => $result['lambda_max'],
            'CI' => $result['CI'],
            'CR' => $result['CR'],
            'RI' => $result['RI'],
            'is_consistent' => $result['is_consistent'],
            'isUsed' => $isUsed
        ]);
    }

    public function normalize(Request $request, AHPService $ahp){
        $idCommittee = getCurrentCommitteeId($request);
        $comparisons = $request->comparisons;
        $division = $request->division;

        if(!manageDivision($division, $request)){
            return response()->json([
                'message' => 'Anda hanya dapat mengelola divisi anda sendiri',
                'type' => 'warning'
            ]);;
        }

        $isUsed = DB::table('tInterviewEvaluationScores as ies')
                        ->join('tInterviewCriterias as ic', 'ies.idInterviewCriterias', 'ic.idInterviewCriterias')
		                ->join('tInterviewDivisionAHPCriterias as idac', 'ic.idInterviewCriterias', 'idac.idInterviewCriterias')
		                ->join('tListDivisionAHPCriterias as ldac', 'idac.idListDivisionAHPCriterias', 'ldac.idListDivisionAHPCriterias')
                        ->where('ldac.idCommittees', $idCommittee)
                        ->where('ldac.idDivisions', $division)
                        ->exists();
        

        // simpen pairwise
        foreach($comparisons as $comp){
            DB::table('tPairwiseComparisons')
            ->updateOrInsert([
                'idCommittees'=>$idCommittee,
                'idDivisions'=> $division,
                'idCriteria1'=> $comp['c1'],
                'idCriteria2'=> $comp['c2'],
            ],
            [
                'weight'=>$comp['value']
            ]);
        }

        // ambil criteria
        $criterias = DB::table('tListDivisionAHPCriterias as lc')
            ->join('tAHPCriterias as ac', 'lc.idAHPCriterias', 'ac.idAHPCriterias')
            ->where('lc.idDivisions', $division)
            ->where('lc.idCommittees', $idCommittee)
            ->select('ac.idAHPCriterias', 'ac.name')
            ->get()
            ->values();

        // ambil pairwise
        $pairwise = DB::table('tPairwiseComparisons')
            ->where('idCommittees', $idCommittee)
            ->where('idDivisions', $division)
            ->get();

        // hitung ahp
        $result = $ahp->calculate($criterias, $pairwise);

        $priority = $result['priority_vector'];

        // simpan bobot ke tListDivisionAHPCriterias
        foreach ($criterias as $index => $c) {
            DB::table('tListDivisionAHPCriterias')
                ->where('idDivisions', $division)
                ->where('idCommittees', $idCommittee)
                ->where('idAHPCriterias', $c->idAHPCriterias)
                ->update([
                    'average_weight' => $priority[$index]
                ]);
        }

        $isConsistent = $result['is_consistent'];

        if($isConsistent == true){
            DB::table('tListDivisions')
            ->where('idCommittees', $idCommittee)
            ->where('idDivisions', $division)
            ->update([
                'is_consistent' => 1
            ]);
        }else{
            DB::table('tListDivisions')
            ->where('idCommittees', $idCommittee)
            ->where('idDivisions', $division)
            ->update([
                'is_consistent' => 0
            ]);
        }

        return response()->json([
            'criterias' => $criterias,
            'matrix' => $result['matrix'],
            'column_sum' => $result['column_sum'],
            'normalized' => $result['normalized'],
            'priority_vector' => $priority,
            'weighted_sum' => $result['weighted_sum'],
            'lambda_vector' => $result['lambda_vector'],
            'lambda_max' => $result['lambda_max'],
            'CI' => $result['CI'],
            'CR' => $result['CR'],
            'RI' => $result['RI'],
            'is_consistent' => $isConsistent,
            'is_used' => $isUsed,
            'message' => $isConsistent 
                ? 'Bobot konsisten dan siap digunakan untuk penilaian.' 
                : 'Perbandingan tidak konsisten, silakan atur ulang bobot.',
            'type' => $isConsistent ? 'success' : 'danger',
        ]);
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
