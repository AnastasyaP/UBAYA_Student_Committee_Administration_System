<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
use App\Http\Controllers\CommitteeController;            
use App\Http\Controllers\DivisionController;   
use App\Http\Controllers\LandingPageController;   
use App\Http\Controllers\RegistrationController;   
use App\Http\Controllers\InterviewScheduleController;
use App\Http\Controllers\InterviewCriteriaController;
use App\Http\Controllers\AHPCalculationController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\InterviewScoringController;
use App\Http\Controllers\EvaluationController;


Route::get('/', function(){ return redirect('/login'); });
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:mahasiswa'])->group(function (){
	Route::get('/home', [LandingPageController::class, 'index'])->name('home'); 
	// regis
	Route::get('/detail-committee/{idCommittee}', [LandingPageController::class, 'show'])->name('detail.committee');
	Route::get('/regis-committee/{idCommittee}', [LandingPageController::class, 'create'])->name('regis.committee');
	Route::get('/intv-schedule/{idCommittee}/{idDivision}', [LandingPageController::class, 'intv'])->name('view.scheduleintv');
	Route::post('/submit-regis', [LandingPageController::class, 'store'])->name('regis.store');
	// profile
	Route::get('/lp-profile', [LandingPageController::class, 'profile'])->name('lp.profile');
	Route::put('/lp-profile/edit', [LandingPageController::class, 'editProfilePicture'])->name('lp.profile.edit');
	Route::put('/lp-profile/change-password', [LandingPageController::class, 'changePassword'])->name('lp.pwd.change');
	Route::put('/lp-profile/save-files', [LandingPageController::class, 'saveFiles'])->name('lp.save.files');
	// committee
	Route::get('/committee', [LandingPageController::class, 'committee'])->name('lp.committee');
	Route::get('/members/set-committee/{idCommittee}', function($idCommittee){
		session(['idCommittee' => $idCommittee]);
		return redirect()->route('members.dashboard');
	})->name('members.set.committee')->middleware('auth');
	// evaluation
	Route::get('/form-evaluation/{idCommittee}/{target?}', [LandingPageController::class, 'evaluationForm'])->name('lp.eval');
	Route::get('/form-evaluation/get-criteria/{idCommittee}/{idDivision}', [LandingPageController::class, 'getEvalCriteria'])->name('lp.get.eval');
	Route::post('/form-evaluation/store', [LandingPageController::class, 'storeEvaluation'])->name('lp.store.eval');
	// cold start preference
	Route::post('/save-preference', [LandingPageController::class, 'savePreference'])->name('save.preference');
});

Route::middleware(['auth', 'access.role'])->group(function(){
	Route::get('/members/dashboard', [DashboardController::class, 'index'])->name('members.dashboard');
	// members
	Route::get('/members/list-members', [RegistrationController::class, 'members'])->name('members.member');
	Route::get('/members/add-members/{divisionId}', [RegistrationController::class, 'create'])->name('members.member.add');
	Route::post('/members/invite-members', [RegistrationController::class, 'store'])->name('members.member.invite');
	// regis
	Route::get('/members/registrations', [RegistrationController::class, 'index'])->name('members.registrations');
	Route::get('/members/registration/division/{idDivision}', [RegistrationController::class, 'getRegByDivision'])->name('members.reg.division');
	Route::get('/members/registration/{status?}', [RegistrationController::class, 'getRegByStatus'])->name('members.reg.status');
	Route::get('/members/view-registrations/{idRegis}', [RegistrationController::class, 'show'])->name('members.view.regis');
	Route::put('/members/registrations/accepted/{idRegis}', [RegistrationController::class, 'accept'])->name('members.accept.regis');
	Route::put('/members/registrations/rejected/{idRegis}', [RegistrationController::class, 'reject'])->name('members.reject.regis');
	// interview schedule
	Route::get('/members/schedules',[InterviewScheduleController::class, 'calendar'])->name('members.intv.calendar');
	Route::get('/members/add-schedule', [InterviewScheduleController::class, 'create'])->name('members.intv.add');
	Route::post('/members/store-schedule',[InterviewScheduleController::class, 'store'])->name('members.intv.store');
	Route::put('/members/update-schedule/{idSchedule}', [InterviewScheduleController::class, 'update'])->name('members.intv.update');
	// interview criteria
	Route::get('/members/intv-criteria', [InterviewCriteriaController::class, 'index'])->name('members.intvcriteria');
	Route::get('/members/add-intvcriterias/{idDivision}', [InterviewCriteriaController::class, 'create'])->name('members.intvcriteria.add');
	Route::post('/members/store-intvcriterias', [InterviewCriteriaController::class, 'store'])->name('members.intvcriteria.store');
	// ahp calculation
	Route::get('/members/ahp', [AHPCalculationController::class, 'index'])->name('members.ahpcalc');
	Route::get('/members/ahp/division/{idDivision}/criterias', [AHPCalculationController::class, 'getCriteriasByDivision'])->name('members.ahp.division.criterias');
	Route::post('/members/ahp/normalize', [AHPCalculationController::class, 'normalize'])->name('members.normalize');
	// interview scoring
	Route::post('/members/intv-scoring', [InterviewScoringController::class, 'index'])->name('members.intvscoring');
	Route::post('/members/score', [InterviewScoringController::class, 'store'])->name('members.intvscoring.score');
	// logout dashboard
	Route::get('/exit-member', function () {
		session()->forget('idCommittee');
		return redirect()->route('home');
	})->name('exit.member');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/choose-committees', [CommitteeController::class, 'index'])->name('committees.choose');
	// committee session
	Route::post('set-committee/{idCommittee}', [CommitteeController::class, 'setCommittee'])->name('set.committee');

	Route::get('/add-committees', [CommitteeController::class, 'create'])->name('committees.add');
	Route::post('/store-committees', [CommitteeController::class, 'store'])->name('committees.store');

});

Route::middleware(['auth', 'role:admin', 'check.committee', 'load.committee'])->group( function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	// committee
	Route::get('/committees', [CommitteeController::class, 'index'])->name('committees');
	Route::get('/profile', [CommitteeController::class, 'profile'])->name('committees.profile');
	Route::get('/committee/get-template/{name}', [CommitteeController::class, 'getTemplate'])->name('committee.template');
	Route::get('/edit-committees/{idCommittees}', [CommitteeController::class, 'show'])->name('committees.show');
	Route::put('/committees/{idCommittees}', [CommitteeController::class, 'update'])->name('committees.update');
	// division
	Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions');
	Route::get('/add-divisions', [DivisionController::class, 'create'])->name('divisions.add');
	Route::post('/store-divisions', [DivisionController::class, 'store'])->name('division.store');
	Route::delete('/divisions/{idDivisions}/committees/{idCommittees}', [DivisionController::class, 'destroy'])->name('division.destroy');
	Route::get('/edit-divisions/{idDivisions}/{idCommittees}', [DivisionController::class, 'edit'])->name('division.edit');
	Route::put('/divisions/{idDivisions}/{idCommittees}', [DivisionController::class, 'update'])->name('division.update');
	// regis
	Route::get('registrations', [RegistrationController::class, 'index'])->name('registration');
	Route::get('/registration/division/{idDivision}', [RegistrationController::class, 'getRegByDivision'])->name('reg.division');
	Route::get('/registration/{status?}', [RegistrationController::class, 'getRegByStatus'])->name('reg.status');
	Route::get('/view-registrations/{idRegis}', [RegistrationController::class, 'show'])->name('view.regis');
	Route::put('/registrations/accepted/{idRegis}', [RegistrationController::class, 'accept'])->name('accept.regis');
	Route::put('/registrations/rejected/{idRegis}', [RegistrationController::class, 'reject'])->name('reject.regis');
	// interview schedule
	// Route::get('schedule-interviews',[InterviewScheduleController::class, 'index'])->name('intv');
	Route::get('/schedules',[InterviewScheduleController::class, 'calendar'])->name('intv.calendar');
	Route::get('/add-schedule', [InterviewScheduleController::class, 'create'])->name('intv.add');
	Route::post('/store-schedule',[InterviewScheduleController::class, 'store'])->name('intv.store');
	Route::put('/update-schedule/{idSchedule}', [InterviewScheduleController::class, 'update'])->name('intv.update');
	// members
	Route::get('/members', [RegistrationController::class, 'members'])->name('member');
	Route::put('/update-position/{memberId}/{divisionId}/{newPosition}', [RegistrationController::class, 'updatePosition'])->name('position.update');
	Route::get('/add-members/{divisionId}', [RegistrationController::class, 'create'])->name('member.add');
	Route::post('/invite-members', [RegistrationController::class, 'store'])->name('member.invite');
	// email invitation
	Route::get('/invitation/{token}', [EmailController::class,'show']);
	Route::post('/invitation/accept/{token}', [EmailController::class,'accept']);
	// Route::post('/invitation/reject/{token}', [EmailController::class,'reject']);
	// interview criteria
	Route::get('/intv-criteria', [InterviewCriteriaController::class, 'index'])->name('intvcriteria');
	Route::get('/add-intvcriterias/{idDivision}', [InterviewCriteriaController::class, 'create'])->name('intvcriteria.add');
	Route::post('/store-intvcriterias', [InterviewCriteriaController::class, 'store'])->name('intvcriteria.store');
	// ahp calculation
	Route::get('/ahp', [AHPCalculationController::class, 'index'])->name('ahpcalc');
	Route::get('/ahp/division/{idDivision}/criterias', [AHPCalculationController::class, 'getCriteriasByDivision'])->name('ahp.division.criterias');
	Route::post('/ahp/normalize', [AHPCalculationController::class, 'normalize'])->name('normalize');
	// interview scoring
	Route::post('/intv-scoring', [InterviewScoringController::class, 'index'])->name('intvscoring');
	// Route::get('/intvscoring/{idMahasiswa}/{idRegis}/{idDivision}', [InterviewScoringController::class, 'index'])->name('intvscoring.get');
	Route::post('/score', [InterviewScoringController::class, 'store'])->name('intvscoring.score');
	//evaluation
	Route::get('/evaluation-criteria/{target?}', [EvaluationController::class, 'index'])->name('evalcriteria');
	Route::get('/add/evaluation-criteria', [EvaluationController::class, 'create'])->name('evalcriteria.add');
	Route::post('/store/evaluation-criteria', [EvaluationController::class, 'store'])->name('evalcriteria.store');
	
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
});