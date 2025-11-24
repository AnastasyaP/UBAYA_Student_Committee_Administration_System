<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterviewSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InterviewScheduleController extends Controller
{
    public function calendar()
    {
        $events = [];

        $admin = Auth::user();

        $idCommittee = DB::table('tUsers as u')
                        ->join('tCommittees as c', 'u.idUsers', 'c.admin')
                        ->where('c.is_active', 1)
                        ->select('c.idCommittees as idCommittee')
                        ->first();
 
        $intvs = DB::table('tUsers as u')
                    ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
                    ->join('tInterviewSchedules as i', 'r.idInterviewSchedules', 'i.idInterviewSchedules')
                    ->join('tListDivisions as ld', function($join){
                        $join->on('i.idDivisions', '=', 'ld.idDivisions');
                        $join->on('i.idCommittees', '=', 'ld.idCommittees');
                    })
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('r.idCommittees', $idCommittee->idCommittee)
                    ->select(
                        'i.idInterviewSchedules',
                        'i.date',
                        'i.start_time',
                        'i.end_time',
                        'i.place',
                        'i.link',
                        DB::raw("concat(u.firstname, ' ', u.lastname) as username"),
                        'd.name as division_name'
                    )
                    ->get();

        foreach ($intvs as $intv) {
            $events[] = [
                'title' => $intv->division_name . ' - ' . $intv->username,
                'description' => 'Place: ' . ($intv->place),
                'start' => $intv->date . 'T' . $intv->start_time,
                'end' => $intv->end_time,
                'url' => $intv->link ?? '#',
            ];
        }
 
        return view('pages.scheduleinterview.scheduleintv', compact('events'));

        // $events = InterviewSchedule::all()->map(function ($item) {
        //     return [
        //         'title' => $item->place,
        //         'start' => $item->date . 'T' . $item->time,
        //         'url'   => $item->link,
        //     ];
        // });

        // return view('pages.scheduleinterview.scheduleintv', compact('events'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('pages.scheduleinterview.scheduleintv');
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
