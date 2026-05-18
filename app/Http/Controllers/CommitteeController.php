<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Committee;
use App\Models\OrganizerUnit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class CommitteeController extends Controller
{
    // set session displayed committtee
    public function setCommittee($idCommittee){
        session(['displayed_committee' => $idCommittee]);

        $committee = DB::table('tCommittees')
        ->where('idCommittees', $idCommittee)
        ->first();

        return redirect('/dashboard')->with('success', 'Kepanitiaan ' . $committee->name . ' berhasil diterapkan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // ngambil user yg login
        $isSuper = false;
        if($user->role === "admin"){
            $admin = DB::table('tUsers as u')
                ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                ->where('a.idUsers', $user->idUsers)
                ->select('a.is_superAdmin')
                ->get();
            if($admin === 1){
                $isSuper = true;
            }
        }

        if($isSuper === true){
            $committees = Committee::all();
        }else{
            $committees = DB::table('tCommittees as c')
                ->join('tUsers as u', 'c.admin', 'u.idUsers')
                ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
                ->where('c.admin', $user->idUsers)
                ->select([
                    'c.*', 
                    DB::raw("'". $user->email . "'as email"), 
                    'a.idOrganizerUnits as idOrganizerUnits', 
                    'o.name as organizerName', 
                    'c.picture as picture'
                ])
                ->get();
                
            $activeCommittee = false;
            $exists = Committee::where('admin', $user->idUsers)
                        ->where('is_active', 1)
                        ->exists();
            if($exists){
                $activeCommittee = true;
            }
        }
        
        return view('pages.committee.committees', compact('committees', 'activeCommittee'));
    }

    public function publishCommittee($idCommittee, Request $request){

        $committee = DB::table('tCommittees')->where('idCommittees', $idCommittee)->first();

        if($committee->is_published === 0){
            DB::table('tCommittees')->where('idCommittees', $idCommittee)->update(['is_published' => 1]);

            return back()->with('success', 'Committee dipublikasi di website mahasiswa!');
        }else{
            DB::table('tCommittees')->where('idCommittees', $idCommittee)->update(['is_published' => 0]);

            return back()->with('success', 'Committee tidak dipublikasi di website mahasiswa!');
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->init();

        $committee = DB::table('tCommittees as c')
                        ->join('tUsers as u', 'c.admin', 'u.idUsers')
                        ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                        ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
                        ->where('c.admin', Auth::id())
                        ->select('u.email as email', 'o.name as organizerName')
                        ->first();   
        
        $master_committee = DB::table('tCommittees')
                            ->select('committee_name')
                            ->where('admin', Auth::id())
                            ->distinct()
                            ->pluck('committee_name');

        return view('pages.committee.add-committees', compact('committee', 'master_committee'));
    }

    public function getTemplate($name){
        $committee = Committee::where('committee_name', $name)
                        ->orderBy('end_period', 'desc')
                        ->first();

        if($committee){
            return response()->json([
                'description' => $committee->description,
                'requirement' => $committee->requirements,
            ]);
        }

        return response()->json(null);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $this->init();

        // dd($request);
        $request->validate([
            'name' => 'required|string|max:45',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'contact' => 'required|string|max:45',
            'start_period' => 'required|date',
            'end_period' => 'required|date',
            'start_regis' => 'required|date',
            'end_regis' => 'required|date',
            'end_eval' => 'required|date',
            'committee_name' => 'nullable',
            'master_committee' => 'nullable',
            'description' => 'required|string|max:600',
            'requirement' => 'required|string|max:500',
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        // dd($request->committee_name);
        $committee_name = "";
        if($request->master_committee){
            $committee_name = $request->master_committee;
        } else if($request->committee_name){
            $committee_name = $request->committee_name;
        }

        // poster
        $filePathPoster = null;
        if($request->hasFile('poster')){
            $filePoster = $request->file('poster');
            $fileNamePoster = Str::uuid() . '.' . $filePoster->getClientOriginalExtension(); //biar namanya unik pas disimpan pakai uuid
            $filePathPoster = $filePoster->storeAs('img/committee/poster', $fileNamePoster, 'public');
        }

        // picture
        $filePathPicture = null;
        if($request->hasFile('picture')){
            $filePicture = $request->file('picture');
            $fileNamePicture = Str::uuid() . '.' . $filePicture->getClientOriginalExtension(); //biar namanya unik pas disimpan pakai uuid
            $filePathPicture = $filePicture->storeAs('img/committee/picture', $fileNamePicture, 'public');
        }

        // dd($admin->idUsers);
        Committee::create([
            'admin' => Auth::id(),
            'committee_name' => $committee_name,
            'name' => $request->name,
            'start_period' => $request->start_period,
            'end_period' => $request->end_period,
            'start_regis' => $request->start_regis,
            'end_regis' => $request->end_regis,
            'end_evaluation' => $request->end_eval,
            'description' => $request->description,
            'requirements' => $request->requirement,
            'contact' => $request->contact,
            'picture' => $filePathPicture,
            'poster' => $filePathPoster,
            'is_active' => 1,
        ]);
        return redirect()->route('committees')->with('success', 'Berhasil Menambahkan Divisi Baru!');
    }

    /**
     * Display the specified resource.
     */
    public function show($idCommittee)
    {
        $committees = DB::table('tCommittees as c')
        ->join('tUsers as u', 'c.admin', 'u.idUsers')
        ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
        ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
        ->where('c.idCommittees', $idCommittee)
        ->select(
            'c.*',
            DB::raw("'". Auth::user()->email . "'as email"), 
            'a.idOrganizerUnits as idOrganizerUnits', 
            'o.name as organizerName', 
            )
        ->get();

        $master_committee = DB::table('tCommittees')
                            ->select('committee_name')
                            ->where('admin', Auth::id())
                            ->distinct()
                            ->pluck('committee_name');
        
        return view('pages.committee.edit-committees', compact('committees', 'master_committee'));
    }

    public function profile(Request $request)
    {
        // $this->init();
        $displayedCommittee = $request->get('displayed_committee');

        // $committees = Committee::all();
        $committees = DB::table('tCommittees as c')
        ->join('tUsers as u', 'c.admin', 'u.idUsers')
        ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
        ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
        ->where('c.is_active', 1)
        ->where('c.idCommittees', $displayedCommittee->idCommittees)
        ->select([
            'c.*', 
            DB::raw("'". Auth::user()->email . "'as email"), 
            'a.idOrganizerUnits as idOrganizerUnits', 
            'o.name as organizerName', 
            'c.picture as picture'
        ])
        ->first();

        // dd($committees);
        
        return view('pages.committee.profile', compact('committees'));
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
    public function update(Request $request, $idCommittee)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'contact' => 'required|string|max:45',
            'start_period' => 'required|date',
            'end_period' => 'required|date',
            'start_regis' => 'required|date',
            'end_regis' => 'required|date',
            'end_eval' => 'required|date',
            'committee_name' => 'nullable',
            'master_committee' => 'nullable',
            'description' => 'required|string|max:600',
            'requirement' => 'required|string|max:500',
            'is_active' => 'nullable',
            'evaluation' => 'nullable',
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $oldData = DB::table('tCommittees')
        ->where('idCommittees', $idCommittee)
        ->first();

        // poster
        $filePathPoster = $oldData->poster;
        if($request->hasFile('poster')){
            if($filePathPoster && Storage::disk('public')->exists($filePathPoster)){
                Storage::disk('public')->delete($filePathPoster);
            }

            $filePoster = $request->poster;
            $fileNamePoster = Str::uuid() . '.' . $filePoster->getClientOriginalExtension();
            $filePathPoster = $filePoster->storeAs('img/committee/poster', $fileNamePoster, 'public');
        }

        //picture
        $filePathPicture = $oldData->picture;
        if($request->hasFile('picture')){
            if($filePathPicture && Storage::disk('public')->exists($filePathPicture)){
                Storage::disk('public')->delete($filePathPicture);
            }

            $filePicture = $request->picture;
            $fileNamePicture = Str::uuid() . '.' . $filePicture->getClientOriginalExtension();
            $filePathPicture = $filePicture->storeAs('img/committee/picture', $fileNamePicture, 'public');
        }

        $committee_name = "";
        if($request->master_committee){
            $committee_name = $request->master_committee;
        } else if($request->committee_name){
            $committee_name = $request->committee_name;
        }

        DB::table('tCommittees')
        ->where('idCommittees', $idCommittee)
        ->update([
            'name' => $request->name,
            'committee_name' => $committee_name,
            'contact' => $request->contact,
            'start_period' => $request->start_period,
            'end_period' => $request->end_period,
            'start_regis' => $request->start_regis,
            'end_regis' => $request->end_regis,
            'description' => $request->description,
            'requirements' => $request->requirement,
            'is_active' => $request->is_active,
            'evaluation' => $request->evaluation,
            'poster' => $filePath,
        ]);

        return redirect()->route('committees')->with('success', 'Committee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
