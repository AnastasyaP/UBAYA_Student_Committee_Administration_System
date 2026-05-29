<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\OrganizerUnit;
use App\Models\Committee;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.superadmin.dashboard');
    }

    public function admins(){

        $admins = DB::table('tUsers as u')
                    ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                    ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
                    ->where('u.role', 'admin')
                    ->where('a.is_superAdmin', 0)
                    ->select([
                        'u.email',
                        'u.username',
                        'u.role',
                        'u.picture',
                        'u.is_active',
                        'o.name as organizer_unit',
                        'o.level',
                        'a.idAdmins',
                        DB::raw("concat(u.firstname, ' ', u.lastname) as name")
                    ])
                    ->get();
        
        return view('pages.superadmin.admin.admin', compact('admins'));

    }

    public function createAdmin(){
        $organizerUnits = OrganizerUnit::where('is_active', 1)->get();

        return view('pages.superadmin.admin.add-admin', compact('organizerUnits'));
    }

    public function storeAdmin(Request $request){
        $request->validate([
            'organizer_unit' => 'required',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'picture' => 'nullable|max:2048|image|mimes:jpg,jpeg,png',
        ],[
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $filePath = null;
        if($request->hasFile('picture')){
            $file = $request->picture;
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('img/profile', $fileName, 'public');
        }

        // dd($request);

        DB::beginTransaction();

        try{
            $idAdmin = DB::table('tUsers')->insertGetId([
                'email' => $request->email,
                'password' => Hash::make('123'),
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'role' => 'admin',
                'picture' => $filePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // dd($idAdmin);
            DB::table('tAdmins')->insert([
                'is_superAdmin' => 0,
                'idOrganizerUnits' => $request->organizer_unit,
                'idUsers' => $idAdmin,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admins.super')->with('success', 'Berhasil menambahkan admin kepanitiaan baru!');

        }catch(\Exception $ex){

            DB::rollback();
                // dd($ex->getMessage());

            return redirect()->back()->with('error', 'Gagal menambah admin kepanitiaan!');

        }
        
    }

    public function editAdmin($idAdmin){
        $organizerUnits = OrganizerUnit::where('is_active', 1)->get();

        $admin = DB::table('tUsers as u')
                    ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                    ->where('a.idAdmins', $idAdmin)
                    ->select([
                        'a.idOrganizerUnits',
                        'u.email',
                        'u.username',
                        'u.firstname',
                        'u.lastname',
                        'u.picture'
                    ])
                    ->first();

        return view('pages.superadmin.admin.edit-admin', compact('organizerUnits', 'admin', 'idAdmin'));
    }

    public function updateAdmin(Request $request){
        $request->validate([
            'idAdmin' => 'required',
            'organizer_unit' => 'required',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'picture' => 'nullable|max:2048|image|mimes:jpg,jpeg,png',
        ],[
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $oldData = DB::table('tUsers as u')
                        ->join('tAdmins as a', 'u.idUsers' ,'a.idUsers')
                        ->where('a.idAdmins', $request->idAdmin)
                        ->select([
                            'u.picture',
                            'a.idUsers'
                        ])
                        ->first();

        $filePath = $oldData->picture ?? null;

        if($request->hasFile('picture')){
            if($filePath && Storage::disk('public')->exists($filePath)){
                Storage::disk('public')->delete($filePath);
            }

            $file = $request->picture;
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('img/profile', $fileName, 'public');
        }

        DB::beginTransaction();

        try{
            DB::table('tUsers')
            ->where('idUsers', $oldData->idUsers)
            ->update([
                'email' => $request->email,
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'picture' => $filePath,
                'updated_at' => now(),
            ]);

            DB::table('tAdmins')
            ->where('idAdmins', $request->idAdmin)
            ->update([
                'idOrganizerUnits' => $request->organizer_unit,
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admins.super')->with('success', 'Berhasil mengedit admin kepanitiaan!');

        }catch(\Exception $ex){

            DB::rollback();
                // dd($ex->getMessage());

            return redirect()->back()->with('error', 'Gagal mengedit admin kepanitiaan!');

        }
        
    }

    public function statusAdmin($idAdmin){
        $admin = DB::table('tUsers as u')
                    ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                    ->where('a.idAdmins', $idAdmin)
                    ->select(['a.idUsers', 'u.is_active'])
                    ->first();

        if($admin->is_active == 1){
            DB::table('tUsers')->where('idUsers', $admin->idUsers)->update(['is_active' => 0]);
            
            return back()->with('success', 'Admin Kepanitiaan di nonaktifkan!');
        }else{
            DB::table('tUsers')->where('idUsers', $admin->idUsers)->update(['is_active' => 1]);

            return back()->with('success', 'Admin Kepanitiaan di aktifkan!');
        }
    }

    public function students(){

        $students = DB::table('tUsers as u')
                    ->join('tMahasiswas as m', 'u.idUsers', 'm.idUsers')
                    ->where('u.role', 'mahasiswa')
                    ->select([
                        'u.email',
                        'u.username',
                        'u.role',
                        'u.picture',
                        'u.is_active',
                        'm.idMahasiswas',
                        'm.nrp',
                        DB::raw("concat(u.firstname, ' ', u.lastname) as name")
                    ])
                    ->get();
        
        return view('pages.superadmin.student.student', compact('students'));

    }

    public function createStudent(){

        return view('pages.superadmin.student.add-student');
    }

    public function storeStudent(Request $request){
        $request->validate([
            'nrp' => 'required|string|max:45',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'picture' => 'nullable|max:2048|image|mimes:jpg,jpeg,png',
        ],[
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $filePath = null;
        if($request->hasFile('picture')){
            $file = $request->picture;
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('img/profile', $fileName, 'public');
        }

        // dd($request);

        DB::beginTransaction();

        try{
            $idMahasiswa = DB::table('tUsers')->insertGetId([
                'email' => $request->email,
                'password' => Hash::make('123'),
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'role' => 'mahasiswa',
                'picture' => $filePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // dd($idAdmin);
            DB::table('tMahasiswas')->insert([
                'nrp' => $request->nrp,
                'idUsers' => $idMahasiswa,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('students.super')->with('success', 'Berhasil menambahkan akun mahasiswa baru!');

        }catch(\Exception $ex){

            DB::rollback();
                dd($ex->getMessage());

            return redirect()->back()->with('error', 'Gagal menambah akun mahasiswa!');

        }
        
    }

    public function editStudent($idMahasiswa){

        $student = DB::table('tUsers as u')
                    ->join('tMahasiswas as a', 'u.idUsers', 'a.idUsers')
                    ->where('a.idMahasiswas', $idMahasiswa)
                    ->select([
                        'a.nrp',
                        'u.email',
                        'u.username',
                        'u.firstname',
                        'u.lastname',
                        'u.picture'
                    ])
                    ->first();

        return view('pages.superadmin.student.edit-student', compact('student', 'idMahasiswa'));
    }

    public function updateStudent(Request $request){
        $request->validate([
            'idMahasiswa' => 'required',
            'nrp' => 'required|string|max:45',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'picture' => 'nullable|max:2048|image|mimes:jpg,jpeg,png',
        ],[
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        $oldData = DB::table('tUsers as u')
                        ->join('tMahasiswas as a', 'u.idUsers' ,'a.idUsers')
                        ->where('a.idMahasiswas', $request->idMahasiswa)
                        ->select([
                            'u.picture',
                            'a.idUsers'
                        ])
                        ->first();

        $filePath = $oldData->picture ?? null;

        if($request->hasFile('picture')){
            if($filePath && Storage::disk('public')->exists($filePath)){
                Storage::disk('public')->delete($filePath);
            }

            $file = $request->picture;
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('img/profile', $fileName, 'public');
        }

        DB::beginTransaction();

        try{
            DB::table('tUsers')
            ->where('idUsers', $oldData->idUsers)
            ->update([
                'email' => $request->email,
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'picture' => $filePath,
                'updated_at' => now(),
            ]);

            DB::table('tMahasiswas')
            ->where('idMahasiswas', $request->idMahasiswa)
            ->update([
                'nrp' => $request->nrp,
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('students.super')->with('success', 'Berhasil mengedit akun mahasiswa!');

        }catch(\Exception $ex){

            DB::rollback();
                // dd($ex->getMessage());

            return redirect()->back()->with('error', 'Gagal mengedit akun mahasiswa!');

        }
        
    }

    public function statusStudent($idMahasiswa){
        $student = DB::table('tUsers as u')
                    ->join('tMahasiswas as m', 'u.idUsers', 'm.idUsers')
                    ->where('m.idMahasiswas', $idMahasiswa)
                    ->select(['m.idUsers', 'u.is_active'])
                    ->first();

        if($student->is_active == 1){
            DB::table('tUsers')->where('idUsers', $student->idUsers)->update(['is_active' => 0]);
            
            return back()->with('success', 'Akun Mahasiswa di nonaktifkan!');
        }else{
            DB::table('tUsers')->where('idUsers', $student->idUsers)->update(['is_active' => 1]);

            return back()->with('success', 'Akun Mahasiswa di aktifkan!');
        }
    }

    public function organizerUnits(){

        $units = OrganizerUnit::all();
        
        return view('pages.superadmin.organizerunit.organizerunit', compact('units'));

    }

    public function createOrganizerUnit(){

        $levels = [
            'universitas',
            'fakultas',
            'program studi'
        ];

        return view('pages.superadmin.organizerunit.add-organizerunit', compact('levels'));
    }

    public function storeOrganizerUnit(Request $request){
        $request->validate([
            'name' => 'required|string|max:45',
            'level' => 'required|in:universitas,fakultas,program studi',
        ],[
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);


            // dd($idAdmin);
        DB::table('tOrganizerUnits')->insert([
            'name' => $request->name,
            'level' => $request->level,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return redirect()->route('units.super')->with('success', 'Berhasil menambahkan unit penyelenggara baru!');
        
    }

    public function editOrganizerUnit($idUnit){

        $levels = [
                'universitas',
                'fakultas',
                'program studi'
            ];

        $unit = DB::table('tOrganizerUnits')
                    ->where('idOrganizerUnits', $idUnit)
                    ->first();

        return view('pages.superadmin.organizerunit.edit-organizerunit', compact('unit', 'idUnit', 'levels'));
    }

    public function updateOrganizerUnit(Request $request){
        $request->validate([
            'name' => 'required|string|max:45',
            'level' => 'required|in:universitas,fakultas,program studi',
        ],[
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);

        DB::table('tOrganizerUnits')
            ->where('idOrganizerUnits', $request->idUnit)
            ->update([
                'name' => $request->name,
                'level' =>$request->level,
                'updated_at' => now(),
            ]);

        return redirect()->route('units.super')->with('success', 'Berhasil mengedit unit penyelenggara!');
        
    }

    public function statusOrganizerUnit($idUnit){
        $unit = DB::table('tOrganizerUnits')->where('idOrganizerUnits', $idUnit)->first();

        if($unit->is_active == 1){
            DB::table('tOrganizerUnits')->where('idOrganizerUnits', $unit->idOrganizerUnits)->update(['is_active' => 0]);
            
            return back()->with('success', 'Unit penyelenggara di nonaktifkan!');
        }else{
            DB::table('tOrganizerUnits')->where('idOrganizerUnits', $unit->idOrganizerUnits)->update(['is_active' => 1]);

            return back()->with('success', 'Unit Penyelenggara di aktifkan!');
        }
    }
    
    public function committees(){

        $committees = DB::table('tCommittees as c')
                            ->join('tUsers as u', 'c.admin', 'u.idUsers')
                            ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                            ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
                            ->select([
                                'c.name',
                                'c.start_period',
                                'c.end_period',
                                'c.start_regis',
                                'c.end_regis',
                                'c.is_active',
                                'c.is_published',
                                'c.idCommittees',
                                'c.picture',
                                'o.name as organizerName'
                            ])
                            ->get();
        
        return view('pages.superadmin.committee.committees', compact('committees'));

    }

    public function statusCommittee($idCommittee){
        $committee = DB::table('tCommittees')->where('idCommittees', $idCommittee)->first();

        if($committee->is_active == 1){
            DB::table('tCommittees')->where('idCommittees', $committee->idCommittees)->update(['is_active' => 0]);
            
            return back()->with('success', 'Kepanitiaan di nonaktifkan!');
        }else{
            DB::table('tCommittees')->where('idCommittees', $committee->idCommittees)->update(['is_active' => 1]);

            return back()->with('success', 'Kepanitiaan di aktifkan!');
        }
    }

    public function detailCommittee($idCommittee){
        $committee = DB::table('tCommittees as c')
                            ->join('tUsers as u', 'c.admin', 'u.idUsers')
                            ->join('tAdmins as a', 'u.idUsers', 'a.idUsers')
                            ->join('tOrganizerUnits as o', 'a.idOrganizerUnits', 'o.idOrganizerUnits')
                            ->where('c.idCommittees', $idCommittee)
                            ->select([
                                'c.*',
                                'o.name as organizerName',
                                'u.email'
                            ])
                            ->first();

        $members = DB::table('tDivisions as d')
                    ->leftJoin('tListDivisions as ld',function($join) use ($idCommittee){
                        $join->on( 'd.idDivisions', '=', 'ld.idDivisions');
                        $join->where('ld.idCommittees', '=', $idCommittee);
                    })
                    ->leftJoin('tRegistrations as r', function($join){
                        $join->on('r.idDivisions', '=', 'ld.idDivisions');
                        $join->on('r.idCommittees', '=', 'ld.idCommittees');
                        $join->where('r.status', 'diterima');
                    })
                    ->leftJoin('tUsers as u', 'r.idUsers','u.idUsers')
                    ->where('ld.idCommittees', $idCommittee)
                    ->select(
                        'r.idUsers as idUser',
                        DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
                        'u.email as email',
                        'd.idDivisions as idDivision',
                        'd.name as division', 
                        'r.position as position'
                    )
                    ->orderby('d.idDivisions')
                    ->get()
                    ->groupby('division');
        
        return view('pages.superadmin.committee.detail-committee', compact('committee', 'members'));
    }
}