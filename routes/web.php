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
	Route::get('/detail-committee/{idCommittee}', [LandingPageController::class, 'show'])->name('detail.committee');
	Route::get('/regis-committee/{idCommittee}', [LandingPageController::class, 'create'])->name('regis.committee');
	Route::get('/intv-schedule/{idCommittee}/{idDivision}', [LandingPageController::class, 'intv'])->name('view.scheduleintv');
	Route::post('/submit-regis', [LandingPageController::class, 'store'])->name('regis.store');
});

Route::middleware(['auth', 'role:admin'])->group( function () {
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
	Route::get('/add-committees', [CommitteeController::class, 'create'])->name('committees.add');
	Route::post('/store-committees', [CommitteeController::class, 'store'])->name('committees.store');
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
	Route::get('/view-registrations/{idRegis}', [RegistrationController::class, 'show'])->name('view.regis');
	Route::put('/registrations/accepted/{idRegis}', [RegistrationController::class, 'accept'])->name('accept.regis');
	Route::put('/registrations/rejected/{idRegis}', [RegistrationController::class, 'reject'])->name('reject.regis');
	// interview schedule
	// Route::get('schedule-interviews',[InterviewScheduleController::class, 'index'])->name('intv');
	Route::get('/schedules',[InterviewScheduleController::class, 'calendar'])->name('intv.calendar');
	Route::get('/add-schedule', [InterviewScheduleController::class, 'create'])->name('intv.add');
	Route::post('/store-schedule',[InterviewScheduleController::class, 'store'])->name('intv.store');
	Route::put('/update-schedule/{idSchedule}', [InterviewScheduleController::class, 'update'])->name('intv.update');
	//members
	Route::get('/members', [RegistrationController::class, 'members'])->name('member');
	Route::put('/update-position/{memberId}/{divisionId}/{newPosition}', [RegistrationController::class, 'updatePosition'])->name('position.update');
	//interview criteria
	Route::get('/intv-criteria', [InterviewCriteriaController::class, 'index'])->name('intvcriteria');
	Route::get('/add-intvcriterias/{idDivision}', [InterviewCriteriaController::class, 'create'])->name('intvcriteria.add');
	Route::post('/store-intvcriterias', [InterviewCriteriaController::class, 'store'])->name('intvcriteria.store');
	// ahp calculation
	Route::get('/ahp', [AHPCalculationController::class, 'index'])->name('ahpcalc');
	Route::get('/ahp/division/{idDivision}/criterias', [AHPCalculationController::class, 'getCriteriasByDivision'])->name('ahp.division.criterias');
	Route::post('/ahp/normalize', [AHPCalculationController::class, 'normalize'])->name('normalize');

	Route::get('/{page}', [PageController::class, 'index'])->name('page');
});