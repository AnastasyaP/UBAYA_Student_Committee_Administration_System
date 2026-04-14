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

    // protected $admin;
    // protected $committee;

    // public function __construct(){
    //     $this->admin = null;
    //     $this->committee = null;
    // }

    // function init(){
    //     $user = Auth::user();

    //     if($user->role != 'admin'){
    //         return redirect()->back()->with('warning', "this account doesn't have an authority");
    //     }

    //     $this->admin = $user;

    //     $idCommittee = session('displayed_committee');

    //     if(!$idCommittee){
    //         return redirect('/kepanitiaan')->with('warning', 'Pilih kepanitiaan dulu');
    //     }

    //     $this->committee = DB::table('tCommittees')
    //         ->where('idCommittees', $idCommittee)
    //         ->first();
    // }

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
                    'u.picture as picture'
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
        return view('pages.committee.add-committees', compact('committee'));
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
            'description' => 'required|string|max:600',
            'requirement' => 'required|string|max:500',
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $filePath = null;
        if($request->hasFile('poster')){
            $file = $request->file('poster');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension(); //biar namanya unik pas disimpan pakai uuid
            $filePath = $file->storeAs('img/committee', $fileName, 'public');
        }

        // dd($admin->idUsers);
        Committee::create([
            'admin' => Auth::id(),
            'name' => $request->name,
            'start_period' => $request->start_period,
            'end_period' => $request->end_period,
            'start_regis' => $request->start_regis,
            'end_regis' => $request->end_regis,
            'description' => $request->description,
            'requirements' => $request->requirement,
            'contact' => $request->contact,
            'poster' => $filePath ,
            'is_active' => 1,
        ]);
        return redirect()->route('committees')->with('success', 'Berhasil Menambahkan Divisi Baru!');
    }

    /**
     * Display the specified resource.
     */
    public function show($idCommittee)
    {
        // $this->init();

        // $committees = Committee::all();
        $committees = DB::table('tCommittees as c')
        ->join('tUsers as u', 'c.admin', 'u.idUsers')
        ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
        ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
        ->where('c.idCommittees', $idCommittee)
        ->select('c.*', DB::raw("'". Auth::user()->email . "'as email"), 'a.idOrganizerUnits as idOrganizerUnits', 'o.name as organizerName', 'u.picture as picture')
        ->get();
        
        return view('pages.committee.edit-committees', compact('committees'));
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
            'u.picture as picture'
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

        $filePath = $oldData->poster;
        if($request->hasFile('poster')){
            if($filePath && Storage::disk('public')->exists($filePath)){
                Storage::disk('public')->delete($filePath);
            }

            $file = $request->poster;
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('img/committee/', $fileName, 'public');
        }

        DB::table('tCommittees')
        ->where('idCommittees', $idCommittee)
        ->update([
            'name' => $request->name,
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
