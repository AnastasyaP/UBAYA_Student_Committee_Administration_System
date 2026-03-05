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
        $pairwiseDB = DB::table('tPairwiseComparisons')
                        ->where('idDivisions', $default)
                        ->where('idCommittees', $this->committee->idCommittees)
                        ->get();

        if($pairwiseDB->isNotEmpty()){
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
        } else{
            $pairwise = [];
            for($i = 0; $i < count($criterias); $i++){
                for($j = $i+1; $j < count($criterias); $j++){
                    $pairwise[] = [
                        'c1'=> $criterias[$i],
                        'c2'=> $criterias[$j],
                    ];
                }
            }
        } 

        return view('pages.ahpcalculation.ahpcalculation', compact('masterDivision', 'default', 'criterias', 'pairwise'));
    }

    public function getCriteriasByDivision($idDivision){
        $this->init();

        $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $this->committee->idCommittees)
                            ->get();

        // $default = $masterDivision->first()->idDivisions;

        $criterias = DB::table('tListDivisionAHPCriterias as lc')
                        ->join('tAHPCriterias as ac', 'lc.idAHPCriterias', 'ac.idAHPCriterias')
                        ->where('lc.idDivisions', $idDivision)
                        ->where('lc.idCommittees', $this->committee->idCommittees)
                        ->get();
        
         $pairwiseDB = DB::table('tPairwiseComparisons')
                        ->where('idDivisions', $idDivision)
                        ->where('idCommittees', $this->committee->idCommittees)
                        ->get();

        if($pairwiseDB->isNotEmpty()){
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
        } else{
            $pairwise = [];
            for($i = 0; $i < count($criterias); $i++){
                for($j = $i+1; $j < count($criterias); $j++){
                    $pairwise[] = [
                        'c1'=> $criterias[$i],
                        'c2'=> $criterias[$j],
                    ];
                }
            }
        } 

        return response()->json([
            'criterias' => $criterias,
            'pairwise' => $pairwise,
            'masterDivision' => $masterDivision
        ]);
    }

    public function normalize(Request $request){
        $this->init();

        $comparisons = $request->comparisons;
        $committee = $this->committee->idCommittees;
        $division = $request->division;

        // simpan pairwise ke tabel pairwise
        foreach($comparisons as $comp){
            DB::table('tPairwiseComparisons')
            ->updateOrInsert([
                'idCommittees'=>$committee,
                'idDivisions'=> $division,
                'idCriteria1'=> $comp['c1'],
                'idCriteria2'=> $comp['c2'],
            ],
            [
                'weight'=>$comp['value']
            ]);
        }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Data pairwise berhasil disimpan'
        // ]);

        // 1️⃣ Ambil semua kriteria pada divisi tersebut
        $criterias = DB::table('tListDivisionAHPCriterias as lc')
            ->join('tAHPCriterias as ac', 'lc.idAHPCriterias', 'ac.idAHPCriterias')
            ->where('lc.idDivisions', $division)
            ->where('lc.idCommittees', $committee)
            ->select('ac.idAHPCriterias', 'ac.name')
            ->get()
            ->values();

        $n = count($criterias);

        // 2️⃣ Buat matriks identitas
        $matrix = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $matrix[$i][$j] = ($i == $j) ? 1 : 0;
            }
        }

        // 3️⃣ Ambil pairwise dari database
        $pairwise = DB::table('tPairwiseComparisons')
            ->where('idCommittees', $committee)
            ->where('idDivisions', $division)
            ->get();

        foreach ($pairwise as $p) {

            $i = $criterias->search(fn($c) => $c->idAHPCriterias == $p->idCriteria1);
            $j = $criterias->search(fn($c) => $c->idAHPCriterias == $p->idCriteria2);

            if ($i !== false && $j !== false) {
                $matrix[$i][$j] = $p->weight;
                $matrix[$j][$i] = 1 / $p->weight;
            }
        }

        // // 4️⃣ Hitung jumlah kolom
        $columnSum = [];

        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $columnSum[$j] = $sum;
        }

        // // 5️⃣ Normalisasi
        $normalized = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $columnSum[$j];
            }
        }

        // // 6️⃣ Priority Vector
        $priority = [];

        for ($i = 0; $i < $n; $i++) {
            $priority[$i] = array_sum($normalized[$i]) / $n;
        }

        // // 7️⃣ Simpan bobot ke tListDivisionAHPCriterias
        foreach ($criterias as $index => $c) {

            DB::table('tListDivisionAHPCriterias')
                ->where('idDivisions', $division)
                ->where('idCommittees', $committee)
                ->where('idAHPCriterias', $c->idAHPCriterias)
                ->update([
                    'average_weight' => $priority[$index]
                ]);
        }

        // 8️⃣ Hitung lambda max
        $lambdaVector = [];

        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $priority[$j];
            }
            $lambdaVector[$i] = $sum / $priority[$i];
        }

        $lambdaMax = array_sum($lambdaVector) / $n;

        // 9️⃣ Hitung CI
        $CI = ($lambdaMax - $n) / ($n - 1);

        // 🔟 Random Index
        $RI_table = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49
        ];

        $RI = $RI_table[$n] ?? 1.49;

        // 1️⃣1️⃣ Hitung CR
        $CR = ($RI == 0) ? 0 : $CI / $RI;

        // 1️⃣2️⃣ Validasi
        $isConsistent = $CR < 0.1;

        return response()->json([
            'matrix' => $matrix,
            'normalized' => $normalized,
            'priority_vector' => $priority,
            'lambda_max' => $lambdaMax,
            'CI' => $CI,
            'CR' => $CR,
            'is_consistent' => $isConsistent
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
