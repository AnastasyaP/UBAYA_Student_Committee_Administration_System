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
                            )
                        ->get();

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

        return view('pages.landingpage.regis-committee', compact('divisions', 'profil'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idUsers' => 'required',
            'idDivisions' => 'required',
            'idCommittees' => 'required',
            'idInterviewSchedules' => 'required',
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

        // ambil committee yg di apply
        $committee = $request->idCommittee;
        // ambil divisi yg di apply
        // nge cek apakah dia uda perna daftar di committee dan divisi itu atao belom
        // max boleh apply 2 divisi!
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
