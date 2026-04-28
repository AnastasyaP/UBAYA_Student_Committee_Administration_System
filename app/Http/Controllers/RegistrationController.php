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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->init();
        $idCommittee = getCurrentCommitteeId($request);
     
        if(!$idCommittee){
            abort(403);
        }

        $registrations = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where(function($query) use ($idCommittee){
            $query->where('c.admin', Auth::id())
                ->orWhereExists(function($q) use ($idCommittee){
                    $q->select(DB::raw(1))
                        ->from('tRegistrations as r2')
                        ->whereColumn('r2.idCommittees', 'c.idCommittees')
                        ->where('r2.idUsers', Auth::id())
                        ->whereIn('r2.position', ['Koordinator', 'Wakil Koordinator', 'BPH-SC'])
                        ->where('r2.status', 'diterima');
                });
        })
        ->where('c.idCommittees', $idCommittee)
        // ->where('r.motivation', '!=', '-')
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
       $regisByDiv = DB::table('tRegistrations as r')
            ->leftJoin('tAHPResults as ar', 'ar.idRegistrations', 'r.idRegistrations')
            ->join('tUsers as u', 'r.idUsers', '=', 'u.idUsers')
            ->join('tMahasiswas as m', 'u.idUsers', '=', 'm.idUsers')
            ->join('tListDivisions as ld', function($join){
                $join->on('r.idDivisions', '=', 'ld.idDivisions');
                $join->on('r.idCommittees', '=', 'ld.idCommittees');
            })
            ->join('tDivisions as d', 'ld.idDivisions', '=', 'd.idDivisions')
            ->join('tCommittees as c', 'ld.idCommittees', '=', 'c.idCommittees')
            ->where(function($query) use ($idCommittee){
                $query->where('c.admin', Auth::id())
                    ->orWhereExists(function($q) use ($idCommittee){
                        $q->select(DB::raw(1))
                            ->from('tRegistrations as r2')
                            ->whereColumn('r2.idCommittees', 'c.idCommittees')
                            ->where('r2.idUsers', Auth::id())
                            ->whereIn('r2.position', ['Koordinator', 'Wakil Koordinator', 'BPH-SC'])
                            ->where('r2.status', 'diterima');
                    });
            })
            ->where('r.idCommittees', $idCommittee)
            ->where('r.status', 'dinilai')
            ->orderBy('ar.final_score', 'desc')
            ->get()
            ->groupBy('division');

            $regisByDiv = $regisByDiv->map(function($items){
                return $items->sortByDesc('final_score')->values();
            });

            // dd($regisByDiv);

            $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $idCommittee)
                            ->get();

            // kalo belum ada data dengan status dinilai maka akan return nama2 divisinya aja
            $regisByDiv = $masterDivision->mapWithKeys(function($div) use ($regisByDiv){
                return [
                    $div->name => $regisByDiv[$div->name] ?? collect()
                ];
            });

        return view('pages.registration.registrations', compact('registrations', 'regisByDiv', 'masterDivision'));
    }
    
    public function getRegByDivision($idDivision, Request $request){
        $idCommittee = getCurrentCommitteeId($request);
     
        if(!$idCommittee){
            abort(403);
        }

        $masterDivision = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $idCommittee)
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
            ->where(function($query) use ($idCommittee){
                $query->where('c.admin', Auth::id())
                    ->orWhereExists(function($q) use ($idCommittee){
                        $q->select(DB::raw(1))
                            ->from('tRegistrations as r2')
                            ->whereColumn('r2.idCommittees', 'c.idCommittees')
                            ->where('r2.idUsers', Auth::id())
                            ->whereIn('r2.position', ['Koordinator', 'Wakil Koordinator', 'BPH-SC'])
                            ->where('r2.status', 'diterima');
                    });
            })
            ->where('r.idCommittees', $idCommittee)
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
            'regByDivision' => $regByDivision->values()
        ]);


    }

    public function getRegByStatus(Request $request){
        $idCommittee = getCurrentCommitteeId($request);
        $status = $request->status;
     
        if(!$idCommittee){
            abort(403);
        }

        $query = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where(function($query) use ($idCommittee){
            $query->where('c.admin', Auth::id())
                ->orWhereExists(function($q) use ($idCommittee){
                    $q->select(DB::raw(1))
                        ->from('tRegistrations as r2')
                        ->whereColumn('r2.idCommittees', 'c.idCommittees')
                        ->where('r2.idUsers', Auth::id())
                        ->whereIn('r2.position', ['Koordinator', 'Wakil Koordinator', 'BPH-SC'])
                        ->where('r2.status', 'diterima');
                });
        })
        ->where('c.idCommittees', $idCommittee);
        // ->where('r.motivation', '!=', '-');
        
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
    public function create($divisionId, Request $request)
    {
        $idCommittee = getCurrentCommitteeId($request);
        if(!$idCommittee){
            abort(403);
        }

        if(!manageDivision($divisionId, $request)){
            return redirect()->back()->with('warning', 
                'Anda hanya dapat mengelola divisi Anda sendiri.');
        }

        $division = DB::table('tDivisions as d')
                            ->join('tListDivisions as ld', 'd.idDivisions', 'ld.idDivisions')
                            ->where('ld.idCommittees', $idCommittee)
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
                            ->where('r.idCommittees', $idCommittee)
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
        $idCommittee = getCurrentCommitteeId($request);
     
        if(!$idCommittee){
            abort(403);
        }

        $committee = DB::table('tCommittees')
                    ->where('idCommittees', $idCommittee)
                    ->first();

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
            'idCommittees' => $idCommittee,
            'status' => 'menunggu',
            'percentage' => 100,
            'position' => $request->position,
            'motivation' => "-",
            'invitation_token' => $token,
            'invitation_expired' => now()->addDays(3)
        ]);

        $division = DB::table('tDivisions')
            ->where('idDivisions', $request->idDivision)
            ->first();

        Mail::to($email)->send(new InviteCommitteeMail($committee->name, $division->name, $request->position, $link));

        return redirect()->back()->with('success', 'Email has successfully sended!');

    }

    /**
     * Display the specified resource.
     */
    public function show($idRegis, Request $request)
    {
        $idCommittee = getCurrentCommitteeId($request);

        $registration = DB::table('tMahasiswas as m')
        ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
        ->join('tRegistrations as r', 'u.idUsers', 'r.idUsers')
        ->join('tListDivisions as ld', function($join){
            $join->on('r.idDivisions', '=', 'ld.idDivisions');
            $join->on('r.idCommittees', '=', 'ld.idCommittees');
        })        
        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
        ->where(function($query) use ($idCommittee){
            $query->where('c.admin', Auth::id())
                ->orWhereExists(function($q) use ($idCommittee){
                    $q->select(DB::raw(1))
                        ->from('tRegistrations as r2')
                        ->whereColumn('r2.idCommittees', 'c.idCommittees')
                        ->where('r2.idUsers', Auth::id())
                        ->whereIn('r2.position', ['Koordinator', 'Wakil Koordinator', 'BPH-SC'])
                        ->where('r2.status', 'diterima');
                });
        })
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

    public function accept($idRegis, Request $request){
        $mhs = DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->select('*')
                ->first();

        
        if(!manageDivision($mhs->idDivisions, $request)){
            return redirect()->back()->with('warning', 
                'Anda hanya dapat menerima pendaftar pada divisi Anda sendiri.');
        }

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

        if (session()->has('idCommittee')) {
            return redirect()->route('members.registrations')->with('success', 'Status updated!');
        }else{
            return redirect()->route('registration')->with('success', 'Status updated!');
        }
    }

    public function reject($idRegis){
        $mhs = DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->select('*')
                ->first();

        
        if(!manageDivision($mhs->idDivisions, $request)){
            return redirect()->back()->with('warning', 
                'Anda hanya dapat menolak pendaftar pada divisi Anda sendiri.');
        }

        if($mhs->status === "dinilai"){
            DB::table('tRegistrations')
                ->where('idRegistrations', $idRegis)
                ->update(['status'=> 'ditolak']);
        } elseif($mhs->status === "diterima"){
            return redirect()->back()->with('warning', 'Pendaftar ini sudah diterima');
        } elseif($mhs->status === 'ditolak'){
            return redirect()->back()->with('warning', 'Pendaftar ini sudah ditolak');
        }

        if (session()->has('idCommittee')) {
            return redirect()->route('members.registrations')->with('success', 'Status updated!');
        }else{
            return redirect()->route('registration')->with('success', 'Status updated!');
        }
    }

    public function members(Request $request){
        $idCommittee = getCurrentCommitteeId($request);
     
        if(!$idCommittee){
            abort(403);
        }

        //  $committee = DB::table('tCommittees')
        //             ->where('idCommittees', $idCommittee)
        //             ->first();

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

        return view('pages.members.members', compact('members'));
    }

    public function updatePosition($memberId, $divisionId, $newPosition, Request $request){
        $idCommittee = getCurrentCommitteeId($request);
     
        if(!$idCommittee){
            abort(403);
        }

        $user = Auth::user();
        $admin = null;

        if($user->role == 'admin'){
            $admin = $user;
        } else{
            return response()->json([
                'success' => false,
                'message' => "Anda tidak memiliki akses untuk mengubah posisi."
            ], 403);
        }

        DB::table('tRegistrations as r')
                ->where('idCommittees', $idCommittee)
                ->where('idUsers', $memberId)
                ->where('idDivisions', $divisionId)
                ->update(['position' => $newPosition]);
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil merubah posisi'
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
