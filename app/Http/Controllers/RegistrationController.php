<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteCommitteeMail;

class RegistrationController extends Controller
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

        $registrations = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where('c.admin', $this->admin->idUsers)
        ->where('c.is_active', 1)
        ->select(
            DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
            'u.email as email',
            'm.nrp as nrp',
            'm.cv as cv',
            'm.portofolio as portofolio',
            'r.status as status',
            'r.idDivisions as idDivision',
            'd.name as division',
            'r.idRegistrations as idRegis',
            'r.idUsers as idMahasiswa',
        )
        ->get();

        // list registrations yang sudah dinilai, ditampilkan per divisi
       $regisByDiv = DB::table('tAHPResults as ar')
            ->join('tRegistrations as r', 'ar.idRegistrations', 'r.idRegistrations')
            ->join('tUsers as u', 'r.idUsers', '=', 'u.idUsers')
            ->join('tMahasiswas as m', 'u.idUsers', '=', 'm.idUsers')
            ->join('tListDivisions as ld', function($join){
                $join->on('r.idDivisions', '=', 'ld.idDivisions');
                $join->on('r.idCommittees', '=', 'ld.idCommittees');
            })
            ->join('tDivisions as d', 'ld.idDivisions', '=', 'd.idDivisions')
            ->join('tCommittees as c', 'ld.idCommittees', '=', 'c.idCommittees')
            ->where('c.admin', $this->admin->idUsers)
            ->where('r.idCommittees', $this->committee->idCommittees)
            ->where('r.status', 'dinilai') // 🔥 penting
            ->select(
                DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
                'u.email',
                'm.nrp',
                'm.cv',
                'm.portofolio',
                'r.status',
                'd.name as division',
                'r.idRegistrations as idRegis',
                'r.idUsers as idMahasiswa',
                'ar.final_score as final_score'
            )
            ->orderBy('ar.final_score', 'desc')
            ->get()
            ->groupBy('division');

            $regisByDiv = $regisByDiv->map(function($items){
                return $items->sortByDesc('final_score')->values();
            });

            $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $this->committee->idCommittees)
                            ->get();

            // kalo belum ada data dengan status dinilai maka akan return nama2 divisinya aja
            $regisByDiv = $masterDivision->mapWithKeys(function($div) use ($regisByDiv){
                return [
                    $div->name => $regisByDiv[$div->name] ?? collect()
                ];
            });
        // dd($regisByDiv);

        return view('pages.registration.registrations', compact('registrations', 'regisByDiv', 'masterDivision'));
    }
    
    public function getRegByDivision($idDivision){
        $this->init();

        $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $this->committee->idCommittees)
                            ->get();
        
        $query = DB::table('tRegistrations as r')
            ->leftJoin('tAHPResults as ar', 'ar.idRegistrations', 'r.idRegistrations')
            ->join('tUsers as u', 'r.idUsers', '=', 'u.idUsers')
            ->join('tMahasiswas as m', 'u.idUsers', '=', 'm.idUsers')
            ->join('tListDivisions as ld', function($join){
                $join->on('r.idDivisions', '=', 'ld.idDivisions');
                $join->on('r.idCommittees', '=', 'ld.idCommittees');
            })
            ->join('tDivisions as d', 'ld.idDivisions', '=', 'd.idDivisions')
            ->join('tCommittees as c', 'ld.idCommittees', '=', 'c.idCommittees')
            ->where('c.admin', $this->admin->idUsers)
            ->where('r.idCommittees', $this->committee->idCommittees)
            ->where('r.status', 'dinilai');

        // 🔥 filter berdasarkan dropdown
        if ($idDivision != '') {
            $query->where('r.idDivisions', $idDivision);
        }

        $regByDivision = $query->select(
            DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
            'u.email',
            'm.nrp',
            'r.status',
            'd.name as division',
            'r.idRegistrations as idRegis',
            'r.idUsers as idMahasiswa',
            'ar.final_score'
        )
        ->orderByDesc('ar.final_score') // ranking AHP
        ->get();

        return response()->json([
            'regByDivision' => $regByDivision
        ]);


    }

    public function getRegByStatus($status=null){
         $this->init();

        $query = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where('c.admin', $this->admin->idUsers)
        ->where('c.is_active', 1);
        
        if(!empty($status)){
            $query->where('r.status', $status);
        }

        $regByStatus = $query->select(
            DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
            'u.email as email',
            'm.nrp as nrp',
            'm.cv as cv',
            'm.portofolio as portofolio',
            'r.status as status',
            'r.idDivisions as idDivision',
            'd.name as division',
            'r.idRegistrations as idRegis',
            'r.idUsers as idMahasiswa',
        )
        ->get();

        return response()->json([
            'regByStatus' => $regByStatus
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($divisionId)
    {
        $this->init();

        $division = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $this->committee->idCommittees)
                            ->where('ld.idDivisions', $divisionId)
                            ->select([
                                'd.idDivisions as idDivision',
                                'd.name as division_name',
                                'ld.is_open as is_open',
                                'ld.description as description',
                                'ld.picture as picture'
                            ])
                            ->first();
        
        $invitationList = DB::table('tRegistrations as r')
                            ->join('tUsers as u', 'r.idUsers', 'u.idUsers')
                            ->where('r.idCommittees', $this->committee->idCommittees)
                            ->where('r.idDivisions', $divisionId)
                            ->where('r.idInterviewSchedules', null)
                            ->select([
                                'r.*',
                                'u.email as email',
                                DB::raw("concat(u.firstname, ' ', u.lastname) as name")
                            ])
                            ->get();

        return view('pages.members.add-members', compact('division', 'invitationList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->init();

        $request->validate([
            'idDivision' => 'required',
            'email' => 'required|string',
            'position' => 'required',
        ], [
            'required' => 'Bagian :attribute wajib diisi.',
            'max' => 'Bagian :attribute maksimal :max karakter.',            
            'after_or_equal' => 'Tanggal :attribute harus setelah atau sama dengan tanggal sebelumnya.',
            'image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'mimes' => 'Format file harus jpg, jpeg, atau png.',
        ]);
        
        $email = $request->email;

        $exists = User::where('email', $email)
                ->exists();

        if (!$exists) {
            return redirect()->back()->with('warning', 'User not found! Please check the email.');
        }

        $userID = DB::table('tUsers')
                    ->where('email', $email)
                    ->first();


        $token = Str::random(40);
        $link = url('/invitation/' . $token);

        Registration::create([
            'idUsers' => $userID->idUsers,
            'idDivisions' => $request->idDivision,
            'idCommittees' => $this->committee->idCommittees,
            'status' => 'pending',
            'percentage' => 100,
            'position' => $request->position,
            'motivation' => "-",
            'invitation_token' => $token,
            'invitation_expired' => now()->addDays(3)
        ]);

        $division = DB::table('tDivisions')
            ->where('idDivisions', $request->idDivision)
            ->first();

        Mail::to($email)->send(new InviteCommitteeMail($this->committee->name, $division->name, $request->position, $link));

        return redirect()->back()->with('success', 'Email has successfully sended!');

    }

    /**
     * Display the specified resource.
     */
    public function show($idRegis)
    {
        $this->init();

        $registration = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where('c.admin', $this->admin->idUsers)
        ->where('r.idRegistrations', $idRegis)
        ->whereColumn('r.idCommittees', 'c.idCommittees')
        ->select(
            DB::raw("concat(u.firstname, ' ', u.lastname) as name"),
            'u.email as email',
            'm.nrp as nrp',
            'm.cv as cv',
            'm.portofolio as portofolio',
            'r.status as status',
            'r.idDivisions as idDivision',
            'r.percentage as percentage',
            'r.motivation as motivation',
            'd.name as division',
        )
        ->first();

        return view('pages.registration.view-registrations', compact('registration'));
    }

    public function accept($idRegis){
        $mhs = DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->select('*')
                ->first();

        $exist = DB::table('tRegistrations')
                ->where('idUsers', '=', $mhs->idUsers)
                ->where('status','=', 'accepted')
                ->where('idDivisions', '!=', $mhs->idDivisions)
                ->first();
        
        if($exist){
            return redirect()->back()->with('warning', "This Applicant is already accepted in another division!");
        }

        if($mhs->status === "dinilai"){
            DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->update(['status'=> 'diterima']);
        } elseif($mhs->status === "diterima"){
            return redirect()->back()->with('warning', 'Pendaftar ini sudah diterima');
        } elseif($mhs->status === 'ditolak'){
            return redirect()->back()->with('warning', 'Pendaftar ini sudah ditolak');
        }
        return redirect()->route('registration')->with('success', 'Status updated!');
    }

    public function reject($idRegis){
        $mhs = DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->select('*')
                ->first();
        if($mhs->status === "dinilai"){
            DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->update(['status'=> 'ditolak']);
        } elseif($mhs->status === "diterima"){
            return redirect()->back()->with('warning', 'Pendaftar ini sudah diterima');
        } elseif($mhs->status === 'ditolak'){
            return redirect()->back()->with('warning', 'Pendaftar ini sudah ditolak');
        }
        return redirect()->route('registration')->with('success', 'Status updated!');
    }

    public function members(){
        $this->init();

        $committee = $this->committee;

        $members = DB::table('tDivisions as d')
                    ->leftJoin('tListDivisions as ld',function($join) use ($committee){
                        $join->on( 'd.idDivisions', '=', 'ld.idDivisions');
                        $join->where('ld.idCommittees', '=', $committee->idCommittees);
                    })
                    ->leftJoin('tRegistrations as r', function($join){
                        $join->on('r.idDivisions', '=', 'ld.idDivisions');
                        $join->on('r.idCommittees', '=', 'ld.idCommittees');
                        $join->where('r.status', 'diterima');
                    })
                    ->leftJoin('tUsers as u', 'r.idUsers','u.idUsers')
                    ->where('ld.idCommittees', $this->committee->idCommittees)
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

        return view('pages.members.members', compact('members'));
    }

    public function updatePosition($memberId, $divisionId, $newPosition){
        $user = Auth::user();
        $admin = null;
        if($user->role == 'admin'){
            $admin = $user;
        } else{
            return response()->json([
                'success' => false,
                'message' => "This user doesn't have authority!"
            ], 403);
        }
        $committee = DB::table('tUsers as u')
                        ->join('tCommittees as c', 'u.idUsers', 'c.admin')
                        ->where('c.admin', $admin->idUsers)
                        ->where('is_active', 1)
                        ->first();

        DB::table('tRegistrations as r')
                ->where('idCommittees', $committee->idCommittees)
                ->where('idUsers', $memberId)
                ->where('idDivisions', $divisionId)
                ->update(['position' => $newPosition]);
        
        return response()->json([
            'success' => true,
            'message' => 'Position updated successfully'
        ]);
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
