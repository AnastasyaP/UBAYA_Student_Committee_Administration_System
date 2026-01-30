<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->username;

        $committees = DB::table('tCommittees')
                    ->where('is_active', 1)
                    ->get();

        return view('pages.landingpage.index', compact('committees', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idCommittee)
    {
        // list divisi dr committee yg dipilih
        $divisions = DB::table('tCommittees as c')
                        ->join('tListDivisions as ld', 'c.idCommittees', 'ld.idCommittees')
                        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                        ->where('ld.idCommittees', $idCommittee)
                        ->where('ld.is_open', 1)
                        ->select(
                            'd.name as dname', 
                            'ld.description as description', 
                            'ld.picture as picture',
                            'ld.idCommittees as idCommittee',
                            'ld.idDivisions as idDivision',
                            )
                        ->get();
                        
        $intvSchedules = DB::table('tInterviewSchedules as i')
                            ->select('i.*')
                            ->leftJoin('tRegistrations as r', 'i.idInterviewSchedules', '=','r.idInterviewSchedules')
                            ->where('i.idCommittees', $idCommittee)
                            ->whereNull('r.idInterviewSchedules')
                            ->get()
                            ->groupby('idDivisions');
        
        // user yg lg login (mhs)
        $user = Auth::user();
        $mhs = null;
        if($user->role === 'mahasiswa'){
            $mhs = $user;
        }else{
            return redirect()->back()->with('warning', 'authentication failed!');
        }

        $profil = DB::table('tUsers as u')
                    ->join('tMahasiswas as m', 'u.idUsers', 'm.idUsers')
                    ->where('u.idUsers', $mhs->idUsers)
                    ->select(
                        'u.email as email',
                        DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
                        'm.nrp as nrp',
                        'm.cv as cv',
                        'm.portofolio as portofolio'
                    )
                    ->first();

        return view('pages.landingpage.regis-committee', compact('divisions', 'profil', 'intvSchedules'));
    }

    public function intv($idCommittee, $idDivision){
        $events = [];
        
        $committee = DB::table('tCommittees')
                    ->where('idCommittees', $idCommittee)
                    ->first();

        $intvs = DB::table('tInterviewSchedules as i')
                    ->select(
                        'i.idInterviewSchedules as idSchedule',
                        'i.date as date',
                        'i.start_time as start_time',
                        'i.end_time as end_time',
                        'i.place as place',
                        'i.link as link',
                        DB::raw("CONCAT(u.firstname, ' ', u.lastname) as username"),
                        'd.name as division_name',
                        'ld.idDivisions as division_id',
                        'r.idCommittees',
                        'r.idUsers as mahasiswa'
                    )
                    ->leftJoin('tRegistrations as r', 'i.idInterviewSchedules', '=', 'r.idInterviewSchedules')
                    ->leftJoin('tUsers as u', 'u.idUsers', '=', 'r.idUsers')
                    ->join('tListDivisions as ld', function ($join) {
                        $join->on('i.idDivisions', '=', 'ld.idDivisions')
                            ->on('i.idCommittees', '=', 'ld.idCommittees');
                    })
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('i.idCommittees', $idCommittee)
                    ->where('i.idDivisions', $idDivision)
                    ->get();
        
        foreach ($intvs as $intv) {
            if($intv->mahasiswa == null){
                $events[] = [
                    'id' => $intv->idSchedule,
                    'title' => $intv->division_name . ' - ' . $intv->username,
                    'description' => 'Place: ' . ($intv->place),
                    'start' => $intv->date . 'T' . $intv->start_time,
                    'end' => $intv->date . 'T' . $intv->end_time,
                    'url' => $intv->link ?? '#',
                    'extendedProps' => [
                        'division' => $intv->division_name,
                        'idDivision' => $intv->division_id,
                        'date' => $intv->date,
                        'start_time' => $intv->start_time,
                        'end_time' => $intv->end_time,
                        'place' => $intv->place,
                        'link' => $intv->link,
                    ]
                ];
            } else{
                $events[] = [
                    'id' => $intv->idSchedule,
                    'title' => $intv->division_name . ' - ' . $intv->username,
                    'description' => 'Place: ' . ($intv->place),
                    'start' => $intv->date . 'T' . $intv->start_time,
                    'end' => $intv->date . 'T' . $intv->end_time,
                    'url' => $intv->link ?? '#',
                    'backgroundColor' => '#fd7e14',
                    'borderColor' => '#fd7e14',
                    'extendedProps' => [
                        'division' => $intv->division_name,
                        'idDivision' => $intv->division_id,
                        'date' => $intv->date,
                        'start_time' => $intv->start_time,
                        'end_time' => $intv->end_time,
                        'place' => $intv->place,
                        'link' => $intv->link,
                        'idSchedule' => $intv->idSchedule,
                    ]
                ];
            }

        }
        return view('pages.landingpage.intv-schedule', compact('events', 'committee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'idCommittee' => 'required|exists:tCommittees,idCommittees',
            'divisions' => 'required|array|min:1|max:2',
            'divisions.*.idDivision' => 'required|exists:tDivisions,idDivisions',
            'divisions.*.percentage' => 'required|integer',
            'divisions.*.idInterviewSchedule' => 'required|exists:tInterviewSchedules,idInterviewSchedules',
            'motivation' => 'required'
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);
        
        // cek auth dulu
        $user = Auth::user();
        $mhs = null;
        if($user->role === "mahasiswa"){
            $mhs = $user;
        }else{
            return redirect()->back()->with('warning', 'authentication failed!');
        }

        $uniqueDivisions = collect($request->divisions)
        ->unique('idDivision')
        ->values()
        ->all();

        $dataToInput = [];
        $dataSkipped = [];

        foreach ($uniqueDivisions as $division) {
            $exist = DB::table('tRegistrations')
                ->where('idUsers', $mhs->idUsers)
                ->where('idCommittees', $request->idCommittee)
                ->where('idDivisions', $division['idDivision'])
                ->exists();

            if (!$exist) {
                $dataToInput[] = [
                    'idUsers' => $mhs->idUsers,
                    'idDivisions' => $division['idDivision'],
                    'idCommittees' => $request->idCommittee,
                    'percentage' => $division['percentage'],
                    'idInterviewSchedules' => $division['idInterviewSchedule'],
                    'motivation' => $request->motivation,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }else{
                $dataSkipped[] = $division['idDivision'];   
            }

        }
        //         dd([
        //     'dataToInput' => $dataToInput,
        //     'is_empty' => empty($dataToInput),
        //     'count' => count($dataToInput),
        //     'skipped' => $dataSkipped
        // ]);
        if (empty($dataToInput)) {
                // dd($exist);
                return back()
                    ->withInput()
                    ->with('error', 'All selected divisions already registered.');
        }

        // pake transaction biar kalo satu insert gagal ntik semua di rollback
        DB::transaction(function () use ($dataToInput){
            DB::table('tRegistrations')->insert($dataToInput);
        });

        if (!empty($dataSkipped)) {
        return redirect()
            ->route('detail.committee', ['idCommittee' => $request->idCommittee])
            ->with('success', 'Registration successful. Some divisions were skipped because they were already registered.');
        }
        
        return redirect()->route('detail.committee', ['idCommittee' => $request->idCommittee])->with('success', 'registration form successfully submitted!');
 
    }

    /**
     * Display the specified resource.
     */
    public function show($idCommittee)
    {
        // dd($idCommittee);
        $committee = DB::table('tCommittees')
                    ->where('idCommittees', $idCommittee)
                    ->select('*')
                    ->first();

        if(!$committee){
            abort(404);
        }

        $divisions = DB::table('tListDivisions as ld')
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('ld.idCommittees', $idCommittee)
                    ->where('ld.is_open', 1)
                    ->select('d.name as name', 'ld.description as description', 'ld.picture as picture')
                    ->get();
        return view('pages.landingpage.detail-committee', compact('committee', 'divisions'));
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
