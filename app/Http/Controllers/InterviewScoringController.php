<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InterviewScoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idCommittee = getCurrentCommitteeId($request);

        $idMahasiswa = $request->idMahasiswa;
        $idRegistrations = $request->idRegis;
        $idDivision = $request->idDivision;

        
        if(!manageDivision($idDivision, $request)){
            return redirect()->back()->with('warning', 
                'Anda hanya dapat menilai pendaftar pada divisi Anda sendiri.');
        }

        $idEvaluator = Auth::user();

        $criterias = DB::table('tListDivisionAHPCriterias as la')
                    ->join('tInterviewDivisionAHPCriterias as ia', 'la.idListDivisionAHPCriterias', 'ia.idListDivisionAHPCriterias')
                    ->join('tInterviewCriterias as ic', 'ia.idInterviewCriterias', 'ic.idInterviewCriterias')
                    // JOIN evaluation dulu
                    ->leftJoin('tInterviewEvaluations as ie', function($join) use ($idRegistrations){
                        $join->where('ie.idRegistrations', '=', $idRegistrations);
                    })
                    // baru JOIN score
                    ->leftJoin('tInterviewEvaluationScores as ies', function($join){
                        $join->on('ic.idInterviewCriterias', '=', 'ies.idInterviewCriterias')
                            ->on('ies.idInterviewEvaluations', '=', 'ie.idInterviewEvaluations');
                    })
                    ->where('la.idCommittees', $idCommittee)
                    ->where('la.idDivisions', $idDivision)
                    ->select(
                        'ic.idInterviewCriterias as idCriterias',
                        'ic.name as kriteria',
                        'ies.score as score',
                        'ie.comment as comment',
                    )
                    ->get();

                    // dd($criterias);

        $divisionName = DB::table('tDivisions')
                        ->where('idDivisions', $idDivision)
                        ->first();

        $mahasiswa = DB::table('tMahasiswas as m')
                        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
                        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
                        ->where('r.idRegistrations', $idRegistrations)
                        ->select(
                            'u.idUsers as idUser',
                            DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
                            'u.email as email',
                            'm.nrp as nrp',
                            'm.cv as cv',
                            'm.portofolio as portofolio',
                            'r.motivation as motivation',
                            'r.percentage as percentage',
                            'r.idRegistrations as idRegis',
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
        $request->validate([
            'scores' => 'required|array',
            'scores.*.score' => 'required|numeric|min:0',
            'scores.*.idInterviewCriteria' => 'required',
            'comment' => 'nullable|string'
        ]);
        
        //buat tInterviewEvaluations
        $exist = DB::table('tInterviewEvaluations')
            ->where('idEvaluator', Auth::id())
            ->where('idRegistrations', $request->idRegis)
            ->first();

        if ($exist) {
            DB::table('tInterviewEvaluations')
                ->where('idInterviewEvaluations', $exist->idInterviewEvaluations)
                ->update([
                    'comment' => $request->comment,
                ]);

            $idInterviewEvaluation = $exist->idInterviewEvaluations;
        } else {
            $idInterviewEvaluation = DB::table('tInterviewEvaluations')
                ->insertGetId([
                    'idEvaluator' => Auth::id(),
                    'idRegistrations' => $request->idRegis,
                    'comment' => $request->comment,
                ]);
        }

        //buat tInterviewEvaluationScores
        foreach($request->scores as $i){
            DB::table('tInterviewEvaluationScores')
            ->updateOrInsert([
                'idInterviewEvaluations' => $idInterviewEvaluation,
                'idInterviewCriterias' => $i['idInterviewCriteria'],
            ],[
                'score' => $i['score']
            ]);

        }
        
        //hitung score
        $avgScores = DB::table('tInterviewEvaluations as ie')
            ->join('tInterviewEvaluationScores as ies', 'ie.idInterviewEvaluations', '=', 'ies.idInterviewEvaluations')
            ->join('tInterviewCriterias as ic', 'ies.idInterviewCriterias', '=', 'ic.idInterviewCriterias')
            ->join('tInterviewDivisionAHPCriterias as idac', 'ic.idInterviewCriterias', '=', 'idac.idInterviewCriterias')
            ->join('tListDivisionAHPCriterias as ldac', 'idac.idListDivisionAHPCriterias', '=', 'ldac.idListDivisionAHPCriterias')
            ->where('ie.idRegistrations', $request->idRegis)
            ->select(
                'ie.idRegistrations',
                'ldac.idAHPCriterias',
                'ldac.average_weight',
                DB::raw('AVG(ies.score) as avg_score')
            )
            ->groupBy(
                'ie.idRegistrations',
                'ldac.idAHPCriterias',
                'ldac.average_weight'
            )
            ->get();

        // foreach avgScores as row
        $finalScore = $avgScores->sum(function ($row) {
            return $row->avg_score * $row->average_weight;
        });

        DB::table('tAHPResults')
            ->updateOrInsert(
            ['idRegistrations' => $request->idRegis], //condition
            ['final_score' => $finalScore] // value
        );
        
        DB::table('tRegistrations')
            ->where('idRegistrations', $request->idRegis)
            ->update([
                'status' => 'dinilai',
            ]);
            
        // dd($avgScores, $finalScore);

        return redirect()->route('registration')->with('success', 'Penilaian berhasil disimpan dan final score berhasil dihitung.');
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
