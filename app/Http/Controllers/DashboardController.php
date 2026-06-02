<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idCommittee = getCurrentCommitteeId($request);

        $menunggu = DB::table('tRegistrations as r')
                    ->join('tListDivisions as ld', function($join){
                        $join->on('r.idCommittees', '=', 'ld.idCommittees');
                        $join->on('r.idDivisions', '=', 'ld.idDivisions');
                    })
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('r.idCommittees', $idCommittee)
                    ->where('r.status', 'menunggu')
                    ->count();

        $dinilai = DB::table('tRegistrations as r')
                    ->join('tListDivisions as ld', function($join){
                        $join->on('r.idCommittees', '=', 'ld.idCommittees');
                        $join->on('r.idDivisions', '=', 'ld.idDivisions');
                    })
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('r.idCommittees', $idCommittee)
                    ->where('r.status', 'dinilai')
                    ->count();

        $diterima = DB::table('tRegistrations as r')
                    ->join('tListDivisions as ld', function($join){
                        $join->on('r.idCommittees', '=', 'ld.idCommittees');
                        $join->on('r.idDivisions', '=', 'ld.idDivisions');
                    })
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('r.idCommittees', $idCommittee)
                    ->where('r.status', 'diterima')
                    ->count();

        $ditolak = DB::table('tRegistrations as r')
                    ->join('tListDivisions as ld', function($join){
                        $join->on('r.idCommittees', '=', 'ld.idCommittees');
                        $join->on('r.idDivisions', '=', 'ld.idDivisions');
                    })
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('r.idCommittees', $idCommittee)
                    ->where('r.status', 'ditolak')
                    ->count();

        $divisions = DB::table('tListDivisions as ld')
                    ->join('tDivisions as d', 'ld.idDivisions', '=', 'd.idDivisions')
                    ->join('tCommittees as c', 'ld.idCommittees', '=', 'c.idCommittees')
                    ->leftJoin('tRegistrations as r', function ($join) {
                        $join->on('ld.idCommittees', '=', 'r.idCommittees')
                            ->on('ld.idDivisions', '=', 'r.idDivisions');
                    })
                    ->where('c.idCommittees', $idCommittee)
                    ->groupBy(
                        'ld.idDivisions',
                        'd.name',
                        'ld.picture',
                        'ld.is_open'
                    )
                    ->select([
                        'd.name',
                        'ld.is_open',
                        'ld.picture',
                        DB::raw('COUNT(r.idRegistrations) as total_applicants')
                    ])
                    ->get();

        $events = [];

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
                    ->get();

        foreach ($intvs as $intv) {
            if($intv->mahasiswa == null){
                $events[] = [
                    'id' => $intv->idSchedule,
                    'title' => $intv->division_name . ' - ' . $intv->username,
                    'description' => 'Place: ' . ($intv->place),
                    'start' => $intv->date . 'T' . $intv->start_time,
                    'end' => $intv->end_time,
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
                    'end' => $intv->end_time,
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

        $masterDivisions = DB::table('tDivisions as d')
                        ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                        ->where('ld.idCommittees', $idCommittee)
                        ->where('ld.is_open', 1)
                        ->get();

        return view('pages.dashboard', compact(
            'menunggu',
            'dinilai',
            'diterima',
            'ditolak',
            'divisions',
            'events',
            'masterDivisions'
        ));
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
