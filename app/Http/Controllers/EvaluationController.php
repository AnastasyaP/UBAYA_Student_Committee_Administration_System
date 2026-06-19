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
        // $criterias = DB::table('tEvaluationCriterias')
        //             ->select('name')
        //             ->distinct()
        //             ->orderBy('name')
        //             ->get();

        $allEvaluationData = DB::table('tEvaluationCriterias')
                                ->select(
                                    'idEvaluationCriterias',
                                    'name',
                                    'description',
                                    'target_type'
                                )
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

        return view('pages.evaluation.add-evalcriteria', compact('divisions', 'masterTarget', 'allEvaluationData'));
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

        try {

            DB::beginTransaction();

            $idCommittee = getCurrentCommitteeId($request);

            $criteria = $request->eval_criteria;
            $selectedCriteria = $request->selected_criteria_id;

            if ($selectedCriteria) {

                $old = DB::table('tEvaluationCriterias')
                    ->where('idEvaluationCriterias', $selectedCriteria)
                    ->first();

                if (
                    $old->description == $request->description &&
                    $old->target_type === $request->target_eval
                ) {

                    $idCriteria = $old->idEvaluationCriterias;

                } else {

                    $idCriteria = DB::table('tEvaluationCriterias')
                        ->insertGetId([
                            'name' => $old->name,
                            'description' => $request->description,
                            'target_type' => $request->target_eval
                        ]);
                }

            } else {

                $exists = DB::table('tEvaluationCriterias')
                    ->where('name', $criteria)
                    ->where('description', $request->description)
                    ->where('target_type', $request->target_eval)
                    ->first();

                if ($exists) {

                    $idCriteria = $exists->idEvaluationCriterias;

                } else {

                    $idCriteria = DB::table('tEvaluationCriterias')
                        ->insertGetId([
                            'name' => $criteria,
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

            if (!$existsScope) {

                DB::table('tEvaluationCriteriaScopes')->insert([
                    'idEvaluationCriterias' => $idCriteria,
                    'idCommittees' => $idCommittee,
                    'idDivisions' => $idDivision,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('evalcriteria')->with('success', 'Berhasil menyimpan kriteria evaluasi baru');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withInput()->with('warning', $e->getMessage());
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
