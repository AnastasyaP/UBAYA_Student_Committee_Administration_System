<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
use App\Http\Controllers\CommitteeController;            
use App\Http\Controllers\DivisionController;   

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function (){
	Route::get('/home', [HomeController::class, 'index'])->name('home'); 
});

Route::group(['middleware' => 'auth:admin'], function () {
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
	Route::get('/profile', [CommitteeController::class, 'show'])->name('committees.profile');
	Route::get('/add-committees', [CommitteeController::class, 'create'])->name('committees.add');
	Route::post('/store-committees', [CommitteeController::class, 'store'])->name('committees.store');
	Route::put('/committees/{idCommittees}', [CommitteeController::class, 'update'])->name('committees.update');
	// division
	Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions');
	Route::get('/add-divisions', [DivisionController::class, 'create'])->name('divisions.add');
	Route::post('/store-divisions', [DivisionController::class, 'store'])->name('division.store');
	Route::delete('/divisions/{idDivisions}/committees/{idCommittees}', [DivisionController::class, 'destroy'])->name('division.destroy');
	Route::get('/edit-divisions/{idDivisions}/{idCommittees}', [DivisionController::class, 'edit'])->name('division.edit');
	Route::put('/divisions/{idDivisions}/{idCommittees}', [DivisionController::class, 'update'])->name('division.update');
	
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
});