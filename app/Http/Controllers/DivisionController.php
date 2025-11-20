<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\ListDivision;
use App\Models\Committee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;


class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Auth::user();
        
        $divisions = DB::table('tDivisions as d')
        ->join('tListDivisions as ld', 'd.idDivisions', '=', 'ld.idDivisions')
        ->join('tCommittees as c', 'ld.idCommittees', '=', 'c.idCommittees')
        ->where('c.admin', $admin->idUsers,)
        ->where('c.is_active', 1)
        ->select('ld.idDivisions as idDivisions', 'ld.idCommittees as idCommittees', 'd.name as name', 'ld.is_open as status', 'ld.description as description', 'ld.picture as picture')
        ->get();

        $activeCommittee = false;
        $exists = Committee::where('admin', $admin->idUsers)
                        ->where('is_active', 1)
                        ->exists();
        if($exists){
            $activeCommittee = true;
        }
        // dd($exists);
        return view('pages.division.divisions', compact('divisions', 'activeCommittee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterDivisions = Division::all();
        return view('pages.division.add-divisions', compact('masterDivisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string|max:600',
            'is_open' => 'required'
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $filePath = null;
        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension(); //biar namanya unik pas disimpan pakai uuid
            $filePath = $file->storeAs('img/division', $fileName, 'public');
        }

        // ambil id committee dr admin yg login
        $admin = Auth::user();
        $committeeId = DB::table('tCommittees as c')
        ->where('c.admin', $admin->idUsers)
        ->where('c.is_active', 1)
        ->value('c.idCommittees');
        
        $name = $request->name; // ambil nama dari text input
        $master_division = $request->master_division; //ambil nama dari combobox


        // klo comboboxnya ada valuenya pake yg dr combobox klo nga buat divisi baru (tapi di cek dulu ada yg sama nga namanya)
        if ($master_division) {
            $divisionId = $master_division;
        } else {
            $existingDivision = Division::where('name', $name)->first();
            if ($existingDivision) { 
                $divisionId = $existingDivision->idDivisions;
            } else {
                $division = Division::create([
                    'name' => $request->name
                ]);
                $divisionId = $division->idDivisions;
            }
        }
        // if(!$masterDivisions){
        //     $division = Division::create([
        //         'name' => $name
        //     ]);
        //     $divisionId = $division->idDivisions;
        // } else{
        //     $division = Division::find($masterDivisions);
        //     $divisionId = $division->idDivisions;
        // }

        // pengecekan klo committee itu uda punya divisi yg mau ditambah or belom
        $exists = ListDivision::where('idDivisions', $divisionId)
                ->where('idCommittees', $committeeId)
                ->exists();

        if ($exists) {
            return redirect()->back()->with('warning', 'Division already exsist for this committee!');
        }

        ListDivision::create([
            'idDivisions' => $divisionId,
            'idCommittees' => $committeeId,
            'is_open' => $request->is_open,
            'description' => $request->description,
            'picture' => $filePath
        ]);
        return redirect()->route('divisions')->with('success', 'Division added Successfully!');
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
    public function edit($idDivisions, $idCommittees)
    {
        $masterDivisions = Division::all();

        $division = DB::table('tCommittees as c')
                    ->join('tListDivisions as ld', 'c.idCommittees', 'ld.idCommittees')
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('ld.idCommittees', $idCommittees)
                    ->where('ld.idDivisions', $idDivisions)
                    ->select('ld.idCommittees as idCommittees', 'ld.idDivisions as idDivisions', 'ld.is_open as is_open', 'ld.description as description', 'ld.picture as picture', 'd.name as name')
                    ->first();
        return view('pages.division.edit-divisions', compact('division', 'masterDivisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idDivisions, $idCommittees)
    {
        // validasi data2 yg masuk (dia membaca dari name yang ada di masing2 widget)
        $request->validate([
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
            'is_open' => 'required|boolean'
        ]);

        // mengambil data divisi lama
        $oldData = DB::table('tListDivisions')
                    ->where('idCommittees', $idCommittees)
                    ->where('idDivisions', $idDivisions)
                    ->first();

        if (!$oldData) {
            return redirect()->back()->with('warning', 'Division not found!');
        }

        //menyimpan gambar klo ada
        $filePath = $oldData->picture ?? null;
        if($request->hasFile('picture')){
            // hapus file lama
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $file = $request->picture;
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('img/division', $fileName, 'public');
        }
        // update ke tabel list divisi
        DB::table('tListDivisions')
                    ->where('idCommittees', $idCommittees)
                    ->where('idDivisions', $idDivisions)
                    ->update([
                        'is_open' => $request->is_open,
                        'description' => $request->description,
                        'picture' => $filePath,
                    ]);
        // return kembali ke division blade dengan kode sukses
        return redirect()->route('divisions')->with('success', 'Division updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idDivisions, $idCommittees)
    {
        $listDivision = DB::table('tListDivisions')
                        ->where('idDivisions','=', $idDivisions)
                        ->where('idCommittees','=', $idCommittees)
                        ->first();

        // dd($listDivision);
        if(!$listDivision){
            return redirect()->back()->with('warning', 'Division not found!');
        }

        if($listDivision->picture && Storage::disk('public')->exists($listDivision->picture)){
            Storage::disk('public')->delete($listDivision->picture);
        }

        // ngabisa pake Elloquent soalnya pknya nga ai dan lebih dari 1
        DB::table('tListDivisions')
                        ->where('idDivisions','=', $idDivisions)
                        ->where('idCommittees','=', $idCommittees)
                        ->delete();

        return redirect()->route('divisions')->with('success', 'Division deleted successfully!');
    }
}
