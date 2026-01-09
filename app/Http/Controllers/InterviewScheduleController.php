<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterviewSchedule;
use App\Models\Division;
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
                        ->where('c.admin', $admin->idUsers)
                        ->where('c.is_active', 1)
                        ->select('c.idCommittees as id')
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
                    ->where('i.idCommittees', $idCommittee->id)
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
                        ->where('ld.idCommittees', $idCommittee->id)
                        ->where('ld.is_open', 1)
                        ->get();
 
        return view('pages.scheduleinterview.scheduleintv', compact('events', 'masterDivisions'));
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
        $admin = Auth::user();
        $idCommittee = DB::table('tUsers as u')
                        ->join('tCommittees as c', 'u.idUsers', 'c.admin')
                        ->where('c.admin', $admin->idUsers)
                        ->where('c.is_active', 1)
                        ->select('c.idCommittees as idCommittee')
                        ->first();

        $divisions = DB::table('tDivisions as d')
                        ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                        ->where('ld.idCommittees', $idCommittee->idCommittee)
                        ->where('ld.is_open', 1)
                        ->get();

        return view('pages.scheduleinterview.add-scheduleintv', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi dulu dongs
        $request->validate([
            'division' => 'required',
            'date' => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i',
            'place' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $user = Auth::user();
        $admin =null;
        if($user->role === 'admin'){
            $admin = $user;
        }

        $idCommittee = DB::table('tUsers as u')
                        ->join('tCommittees as c', 'u.idUsers', 'c.admin')
                        ->where('c.admin', $admin->idUsers)
                        ->where('c.is_active', 1)
                        ->select('c.idCommittees as id')
                        ->first();

        // cek apakah uda ada jadwal divisi yang sama di tgl & jam yg sama (jangan sampe bentrok)
        $existingSchedule = DB::table('tInterviewSchedules')
                                ->where('date', $request->date)
                                ->where('start_time', '<', $request->end_time)
                                ->where('end_time', '>', $request->start_time)
                                ->where('idDivisions', $request->division)
                                ->where('idCommittees', $idCommittee->id)
                                ->first();
        if($existingSchedule){
            return redirect()->back()->with('warning', 'This division already has a schedule in this time!');
        } else{
            if($request->start_time >= $request->end_time){
                return redirect()->back()->with('warning', 'Time Invalid!');
            }
            // klo aman masukin lgsg
            InterviewSchedule::create([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'place' => $request->place ? $request->place : '',
                'link' => $request->link ? $request->link : '',
                'idDivisions' => $request->division,
                'idCommittees' => $idCommittee->id
            ]);

            return redirect()->route('intv.calendar')->with('success', 'Interview Schedule added Successfully!');
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
    public function update(Request $request, $idSchedule)
    {
        \Log::info('Update method triggered for id: '.$idSchedule);
        // validation
        $request->validate([
            'division' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i|before:end_time',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'place' => 'nullable|string|max:225',
            'link' => 'nullable|string|max:500'
        ],[
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);
        // cek di divisi itu pada tlg n jam itu uda ada jadwal atau belom\
        $user = Auth::user();
        $admin = null;
        dd($user);
        if($user->role === 'admin'){
            $admin = $user;
        }

        $idCommittee = DB::table('tCommittees')
                        ->where('admin', $admin->idUsers)
                        ->where('is_active', 1)
                        ->select('idCommittees')
                        ->first();

                        dd($idCommittee);

        $exists = DB::table('tInterviewSchedules')
                    ->where('idDivisions', $request->division)
                    ->where('date', $request->date)
                    ->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time)
                    ->where('idCommittees', $idCommittee->idCommittees)
                    ->where('idInterviewSchedules', '!=', $idSchedule)
                    ->first();

        // klo aman update, klo nga kasi warning uda ada jadwalnya
        if($exists){
            return redirect()->back()->with('warning', 'This division already has a schedule in this time!');
        }else{
            if($request->start_time >= $request->end_time){
                return redirect()->back()->with('warning', 'Time Invalid!');
            }

            DB::table('tInterviewSchedules')
            ->where('idInterviewSchedules', $idSchedule)
            ->update([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'place' => $request->place ? $request->place : '',
                'link' => $request->link ? $request->link : '',
                'idDivisions' => $request->division,
                'idCommittees' => $idCommittee->idCommittees
            ]);
            return redirect()->route('intv.calendar')->with('success', 'Interview Schedule updated Successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
