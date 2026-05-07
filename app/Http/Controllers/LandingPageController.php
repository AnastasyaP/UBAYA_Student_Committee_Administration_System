<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\UBCFService;


class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UBCFService $ubcf)
    {
        $user = Auth::id();

        $needPreferences = false;
        $hasRatings = DB::table('tEvaluations')->where('evaluator_id', $user)->whereNotNull('target_committee')->exists();
        $hasPreference = DB::table('tUserPreferences')->where('idUsers', $user)->exists();
        $hasHistory = DB::table('tRegistrations')->where('idUsers', $user)->where('status', 'diterima')->exists(); 

        $keywords = DB::table('tKeywords')->get();

        if ($hasRatings) {
            $ubcf->generateRecommendations($user);
            $recommendations = DB::table('tRecommendations as r')
                                    ->join('tCommittees as c', 'r.idCommittees', 'c.idCommittees')
                                    ->where('r.idUsers', $user)
                                    ->orderByDesc('predicted_score')
                                    ->select([
                                        'c.*',
                                        'r.predicted_score'
                                    ])
                                    ->get();
        } elseif ($hasPreference) {
            // fallback
            $recommendations = $this->getColdStartRecommendations($user);

        } else {
            // benar-benar cold start
            $recommendations = collect();
            $needPreferences = true;
        }

        $committees = DB::table('tCommittees as c')
                    ->where('is_active', 1)
                    ->select([
                        'c.*'
                    ])
                    ->get();


        return view('pages.landingpage.index', compact(
            'committees', 
            'keywords', 
            'recommendations',
            'needPreferences', 
            'hasHistory'
            ));
    }

    public function getColdStartRecommendations($idUser){
        $keywords = DB::table('tUserPreferences')
                    ->where('idUsers', $idUser)
                    ->pluck('idKeywords');

        if($keywords->isEmpty()){
            $needPreferences = true;
            return collect();
        }

        $committees = DB::table('tCommittees as c')
                    ->join('tListDivisions as ld', 'c.idCommittees', '=', 'ld.idCommittees')
                    ->join('tListDivisionKeywords as dk', 'ld.idDivisions', '=', 'dk.idDivisions')
                    ->whereIn('dk.idKeywords', $keywords)
                    ->select([
                        'c.idCommittees',
                        'c.name',
                        'c.description',
                        'c.picture',
                        'c.admin',
                        DB::raw('COUNT(dk.idKeywords) as match_score')
                    ])
                    ->groupBy([
                        'c.idCommittees',
                        'c.name',
                        'c.description',
                        'c.picture',
                        'c.admin'
                    ])
                    ->orderByDesc('match_score')
                    ->limit(6)
                    ->get();

        return $committees;
    }

    public function savePreference(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'keywords' => 'required|array|min:1'
        ]);

        // hapus preference lama (biar tidak duplicate)
        DB::table('tUserPreferences')
            ->where('idUsers', $userId)
            ->delete();

        foreach ($request->keywords as $keyword) {
            DB::table('tUserPreferences')->insert([
                'idUsers' => $userId,
                'idKeywords' => $keyword,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 🔥 redirect balik ke homepage
        return redirect()->route('home')->with('success', 'Preferensi berhasil disimpan!');
    }
    public function profile(){
        $mahasiswa = null;
        if(Auth::user()->role === 'mahasiswa'){
            $mahasiswa = Auth::user();
        }

        $profile = DB::table('tMahasiswas as m')
                    ->join('tUsers as u', 'm.idUsers', 'u.idUsers')
                    ->where('u.idUsers', $mahasiswa->idUsers)
                    ->first();

        return view('pages.landingpage.profile', compact('profile'));
    }

    public function committee(){
        $committees = DB::table('tRegistrations as r')
                        ->join('tListDivisions as ld', function($join){
                            $join->on('r.idCommittees', '=', 'ld.idCommittees');
                            $join->on('r.idDivisions', '=', 'ld.idDivisions');
                        })
                        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
                        ->join('tUsers as u', 'c.admin', 'u.idUsers')
                        ->where('r.idUsers', Auth::id())
                        ->where('r.status', 'diterima')
                        ->select([
                            'd.name as division',
                            'c.name as committee',
                            'c.idCommittees as idCommittee',
                            'r.position as position',
                            'u.picture as picture',
                            'c.is_active as is_active',
                            'c.start_period as start_period',
                            'c.end_period as end_period',
                            'r.status as status',
                        ])
                        ->get();

         $registrations = DB::table('tRegistrations as r')
                        ->join('tListDivisions as ld', function($join){
                            $join->on('r.idCommittees', '=', 'ld.idCommittees');
                            $join->on('r.idDivisions', '=', 'ld.idDivisions');
                        })
                        ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                        ->join('tCommittees as c', 'ld.idCommittees', 'c.idCommittees')
                        ->join('tUsers as u', 'c.admin', 'u.idUsers')
                        ->where('r.idUsers', Auth::id())
                        ->select([
                            'd.name as division',
                            'c.name as committee',
                            'c.idCommittees as idCommittee',
                            'r.position as position',
                            'u.picture as picture',
                            'r.status as status',
                        ])
                        ->get();


        return view('pages.landingpage.committee', compact('committees', 'registrations'));
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
                            'ld.idCommittees as idCommittee',
                            'ld.idDivisions as idDivision',
                            )
                        ->get();
                        
        $intvSchedules = DB::table('tInterviewSchedules as i')
                            ->select('i.*')
                            ->leftJoin('tRegistrations as r', 'i.idInterviewSchedules', '=','r.idInterviewSchedules')
                            ->where('i.idCommittees', $idCommittee)
                            ->whereNull('r.idInterviewSchedules')
                            ->get()
                            ->groupby('idDivisions');
        
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

        return view('pages.landingpage.regis-committee', compact('divisions', 'profil', 'intvSchedules'));
    }

    public function intv($idCommittee, $idDivision){
        $events = [];
        
        $committee = DB::table('tCommittees')
                    ->where('idCommittees', $idCommittee)
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
                    ->where('i.idCommittees', $idCommittee)
                    ->where('i.idDivisions', $idDivision)
                    ->get();
        
        foreach ($intvs as $intv) {
            if($intv->mahasiswa == null){
                $events[] = [
                    'id' => $intv->idSchedule,
                    'title' => $intv->division_name . ' - ' . $intv->username,
                    'description' => 'Place: ' . ($intv->place),
                    'start' => $intv->date . 'T' . $intv->start_time,
                    'end' => $intv->date . 'T' . $intv->end_time,
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
                    'end' => $intv->date . 'T' . $intv->end_time,
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
        return view('pages.landingpage.intv-schedule', compact('events', 'committee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'idCommittee' => 'required|exists:tCommittees,idCommittees',
            'divisions' => 'required|array|min:1|max:2',
            'divisions.*.idDivision' => 'required|exists:tDivisions,idDivisions',
            'divisions.*.percentage' => 'required|integer',
            'divisions.*.idInterviewSchedule' => 'required|exists:tInterviewSchedules,idInterviewSchedules',
            'motivation' => 'required'
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

        $uniqueDivisions = collect($request->divisions)
        ->unique('idDivision')
        ->values()
        ->all();

        $dataToInput = [];
        $dataSkipped = [];

        foreach ($uniqueDivisions as $division) {
            $exist = DB::table('tRegistrations')
                ->where('idUsers', $mhs->idUsers)
                ->where('idCommittees', $request->idCommittee)
                ->where('idDivisions', $division['idDivision'])
                ->exists();

            if (!$exist) {
                $dataToInput[] = [
                    'idUsers' => $mhs->idUsers,
                    'idDivisions' => $division['idDivision'],
                    'idCommittees' => $request->idCommittee,
                    'percentage' => $division['percentage'],
                    'idInterviewSchedules' => $division['idInterviewSchedule'],
                    'motivation' => $request->motivation,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }else{
                $dataSkipped[] = $division['idDivision'];   
            }

        }
   
        if (empty($dataToInput)) {
                return back()
                    ->withInput()
                    ->with('error', 'All selected divisions already registered.');
        }

        // pake transaction biar kalo satu insert gagal ntik semua di rollback
        DB::transaction(function () use ($dataToInput){
            DB::table('tRegistrations')->insert($dataToInput);
        });

        if (!empty($dataSkipped)) {
        return redirect()
            ->route('detail.committee', ['idCommittee' => $request->idCommittee])
            ->with('success', 'Registration successful. Some divisions were skipped because they were already registered.');
        }
        
        return redirect()->route('detail.committee', ['idCommittee' => $request->idCommittee])->with('success', 'registration form successfully submitted!');
 
    }

    /**
     * Display the specified resource.
     */
    public function show($idCommittee)
    {
        // dd($idCommittee);
        $committee = DB::table('tCommittees as c')
                    ->join('tUsers as u', 'c.admin', 'u.idUsers')
                    ->where('c.idCommittees', $idCommittee)
                    ->select([
                        'c.name as name',
                        'c.poster as poster',
                        'c.description as description',
                        'c.requirements as requirements', 
                        'c.idCommittees as idCommittees',
                        'u.picture as picture',
                    ])
                    ->first();
        // dd($committee);

        if(!$committee){
            abort(404);
        }

        $registrations = DB::table('tRegistrations')
                        ->where('idCommittees', $committee->idCommittees)
                        ->where('idUsers', Auth::id())
                        ->get();
        
        $jmlDaftar = $registrations->count();

        $daftarDiterima = $registrations->where('status', 'diterima')->count() > 0;
        // $daftarDitolak = $registrations->where('status', 'ditolak')->count() > 0;
        // $daftarDinilai = $registrations->where('status', 'dinilai')->count() > 0;
        // $daftarMenunggu = $registrations->where('status', 'menunggu')->count() > 0;

        $allowedToRegister = $jmlDaftar < 2 && !$daftarDiterima;

        $isKoor = $registrations->where('status', 'diterima')
                                ->whereIn('position', ['Koordinator', 'Wakil Koordinator', 'BPH-SC'])
                                ->count() > 0;
        

        $divisions = DB::table('tListDivisions as ld')
                    ->join('tDivisions as d', 'ld.idDivisions', 'd.idDivisions')
                    ->where('ld.idCommittees', $idCommittee)
                    ->where('ld.is_open', 1)
                    ->select('d.name as name', 'ld.description as description', 'ld.picture as picture')
                    ->get();
        return view('pages.landingpage.detail-committee', compact(
            'committee', 
            'divisions', 
            'allowedToRegister', 
            'isKoor',
            'daftarDiterima',
            'jmlDaftar'
            ));
    }

    public function editProfilePicture(Request $request){
        $request->validate([
            'picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ],
        [
            'picture.max' => 'Ukuran gambar maksimal 2MB!',
            'picture.image' => 'File harus berupa gambar!',
            'picture.mimes' => 'Format harus JPG, JPEG, atau PNG!',
            'required' => 'Tolong upload foto terlebih dahulu',
        ]);

        $user = Auth::user();

        $oldData = DB::table('tUsers as u')
                    ->join('tMahasiswas as m', 'u.idUsers', 'm.idUsers')
                    ->where('u.idUsers', $user->idUsers)
                    ->first();

        $filePath = $oldData->picture ?? null;

                // dd($filePath, $request->picture);
        if($request->hasFile('picture')){
            if($filePath && Storage::disk('public')->exists($filePath)){
                Storage::disk('public')->delete($filePath);
            }

            $file = $request->picture;
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('img/profile', $fileName, 'public');
        }

        DB::table('tUsers')
            ->where('idUsers', $user->idUsers)
            ->update([
                'picture' => $filePath,
            ]);
        
        return redirect()->back()->with('success', 'Foto profil berhasil di update!');
    }

    public function changePassword(Request $request){
        $request->validate([
            'old_pwd' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ],[
            'required' => ':attribute harus diisi',
            'confirm_password.same' => 'Konfirmasi password tidak cocok',
        ],[
            'old_pwd' => 'Password Lama',
            'new_password' => 'Password Baru',
            'confirm_password' => 'Konfirmasi Password'
        ]);

        // if($request->new_password != $request->confirm_password){
        //     return redirect()->back()->with('warning', 'Konfirmasi password tidak cocok!');
        // }
        $user = Auth::user();

        if(!Hash::check($request->old_pwd, $user->password)){
            return redirect()->back()->withErrors(['old_pwd' => 'Password lama salah!'])->withInput();
        }

        // $user->password = Hash::make($request->new_password);
        $user->password = $request->new_password; // hasingnya di model
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }

    public function saveFiles(Request $request){
        $request->validate([
            'cv' => 'nullable|file|mimes:pdf|max:2048',
            'portofolio' => 'nullable|file|mimes:pdf|max:2048',
        ],[
            'mimes' => ':attribute harus berupa file PDF',
            'max' => 'Ukuran :attribute maksimal 2MB',
        ],[
            'cv' => 'CV',
            'portofolio' => 'Portofolio',
        ]);

        $user = Auth::user();

        $oldData = DB::table('tMahasiswas')
                    ->where('idUsers', $user->idUsers)
                    ->first();

        $cvPath = $oldData->cv ?? null;
        $portoPath = $oldData->portofolio ?? null;

        if(!$request->hasFile('cv') && !$request->hasFile('portofolio')){
            return redirect()->back()->with('error', 'Tidak ada file yang di upload!');
        }

        $updateData = [];

        if($request->hasFile('cv')){
            if($cvPath && Storage::disk('public')->exists($cvPath)){
                Storage::disk('public')->delete($cvPath);
            }

            $cvFile = $request->file('cv');
            $cvFileName = $user->idUsers . '_' . Str::uuid() . '.pdf';
            $cvFilePath = $cvFile->storeAs('cv', $cvFileName, 'public');

            $updateData['cv'] = $cvFilePath;
        }

        if($request->hasFile('portofolio')){
            if($portoPath && Storage::disk('public')->exists($portoPath)){
                Storage::disk('public')->delete($portoPath);
            }

            $portoFile = $request->file('portofolio');
            $portoFileName = $user->idUsers . '_' . Str::uuid() . '.pdf';
            $portoFilePath = $portoFile->storeAs('portofolio', $portoFileName, 'public');

            $updateData['portofolio'] = $portoFilePath;
        }

        if(!empty($updateData)){
            DB::table('tMahasiswas')
                ->where('idUsers', $user->idUsers)
                ->update($updateData);
        }

        return redirect()->back()->with('success', 'File berhasil di upload!');
    }

    public function evaluationForm(Request $request, $idCommittee, $target = 'committee'){
        $masterTarget = [
            'committee' => 'Kepanitiaan',
            'division' => 'Divisi',
            'user' => 'Panitia',
        ];

        if ($target == 'division') {
            $selectedDivision = old('target_division') ?? $request->target_division;

            $criterias = DB::table('tEvaluationCriterias as ec')
                    ->leftJoin('tEvaluationCriteriaScopes as es', function($join) use ($idCommittee) {
                        $join->on('es.idEvaluationCriterias', '=', 'ec.idEvaluationCriterias')
                            ->where('es.idCommittees', '=', $idCommittee);
                    })
                    ->leftJoin('tDivisions as d', 'es.idDivisions', 'd.idDivisions')
                    ->where('target_type', 'division')
                    ->where('es.idDivisions', $selectedDivision)
                    ->select([
                        'ec.name',
                        'ec.description',
                        'ec.target_type',
                        'es.idDivisions as division',
                        'd.name as division_name',
                        'ec.idEvaluationCriterias'
                    ])
                    ->get();
        }else{
            $criterias = DB::table('tEvaluationCriterias as ec')
                    ->leftJoin('tEvaluationCriteriaScopes as es', function($join) use ($idCommittee) {
                        $join->on('es.idEvaluationCriterias', '=', 'ec.idEvaluationCriterias')
                            ->where('es.idCommittees', '=', $idCommittee);
                    })
                    ->leftJoin('tDivisions as d', 'es.idDivisions', 'd.idDivisions')
                    ->where('ec.target_type', $target)
                    ->select([
                        'ec.name',
                        'ec.description',
                        'ec.target_type',
                        'es.idDivisions as division',
                        'd.name as division_name',
                        'ec.idEvaluationCriterias'
                    ])
                    ->get();
        }

        
        // dd($criterias);

        $evaluatedDivisions = DB::table('tEvaluations')
            ->where('evaluator_id', Auth::id())
            ->where('target_committee', $idCommittee)
            ->whereNotNull('target_division')
            ->pluck('target_division')
            ->toArray();

        $evaluatedUsers = DB::table('tEvaluations')
            ->where('evaluator_id', Auth::id())
            ->where('target_committee', $idCommittee)
            ->whereNotNull('target_user')
            ->pluck('target_user')
            ->toArray();

        $divisions = DB::table('tDivisions as d')
        ->join('tListDivisions as ld', 'd.idDivisions', '=', 'ld.idDivisions')
        ->where('ld.idCommittees', $idCommittee)
        ->whereNotIn('d.idDivisions', $evaluatedDivisions)
        ->get();

        $users = DB::table('tUsers as u')
        ->join('tRegistrations as r', 'u.idUsers', '=', 'r.idUsers')
        ->where('r.idCommittees', $idCommittee)
        ->where('r.status', 'diterima')
        ->where('u.idUsers', '!=', Auth::id())
        ->whereNotIn('u.idUsers', $evaluatedUsers)
        ->select('u.idUsers', 'u.firstname', 'u.lastname')
        ->get();

        $isEvaluatedCommittee = DB::table('tEvaluations')
            ->where('evaluator_id', Auth::id())
            ->where('target_committee', $idCommittee)
            ->whereNull('target_division')
            ->whereNull('target_user')
            ->exists();

        return view('pages.landingpage.form-evaluation', compact(
            'masterTarget', 
            'criterias', 
            'divisions', 
            'users', 
            'target',
            'idCommittee',
            'isEvaluatedCommittee'));
    }

    public function getEvalCriteria($idCommittee, $idDivision){
        $criterias = DB::table('tEvaluationCriterias as ec')
                    ->leftJoin('tEvaluationCriteriaScopes as es', function($join) use ($idCommittee) {
                        $join->on('es.idEvaluationCriterias', '=', 'ec.idEvaluationCriterias')
                            ->where('es.idCommittees', '=', $idCommittee);
                    })        
                    ->leftJoin('tDivisions as d', 'es.idDivisions', 'd.idDivisions')
                    ->where('ec.target_type', 'division')
                    ->where('es.idDivisions', $idDivision)
                    ->select([
                        'ec.name',
                        'ec.description',
                        'ec.target_type',
                        'es.idDivisions as division',
                        'd.name as division_name',
                        'ec.idEvaluationCriterias'
                    ])
                    ->get();

        return response()->json([
            'criterias' => $criterias
            ]);
    }

    public function storeEvaluation(Request $request){
        $request->validate([
            'scores' => 'required|array|min:1',
            'scores.*.score' => 'required|integer|min:1|max:5',
            'overall_score' => 'required|integer|min:1|max:5',
            'target_committee' => 'required',
            'target_division' => 'required_if:target,division',
            'target_user' => 'required_if:target,user',
        ]);

        // target
        $targetUser = $request->target_user;
        $targetDivision = $request->target_division;
        $targetCommittee = $request->target_committee;

        // db transaction ->  semua proses harus berhasil or gagal semua (do or die)
        DB::beginTransaction();
        try{
            $idEvaluation = DB::table('tEvaluations')
                            ->insertGetId([
                                'evaluator_id' => Auth::id(),
                                'target_committee' => $targetCommittee,
                                'target_division' => $targetDivision,
                                'target_user' => $targetUser,
                                'comment' => $request->overall_comment,
                                'ratings' => $request->overall_score,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
            
            foreach($request->scores as $idCriteria => $data){
                DB::table('tEvaluationScores')
                ->insert([
                    'idEvaluations' => $idEvaluation,
                    'idEvaluationCriterias' =>$idCriteria,
                    'score' => $data['score'],
                    'comment' => $data['comment'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit(); // kalo pakai transaction harus di commit biar kesimpan semua, klo insert doang nanti ketahan

            return redirect()->back()->with('success', 'Berhasil menyimpan evaluasi!');
        }catch(\Exception $ex){
            DB::rollback();

            return redirect()->back()->with('error', 'Gagal menyimpan evaluasi!');
        }

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
