<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PracticeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| schoolar.ai — routes
|--------------------------------------------------------------------------
| Every link in the sidebar resolves to one of the named routes below, so
| no page can throw "Route [x] not defined".
*/

// "/" lands on the dashboard.
Route::redirect('/', '/dashboard');

// ---- The four real pages -------------------------------------------------
Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::view('/live-classes', 'live-classes')->name('live-classes.index');

Route::view('/mira', 'mira')->name('mira.index');

Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');

Route::view('/mock-tests/history', 'mock-tests.history')->name('mock-history.index');
Route::post('/practice', [PracticeController::class, 'api'])->name('practice.api');

// ---- Supporting pages ----------------------------------------------------
Route::get('/my-tests', [PageController::class, 'mockTests'])->name('mock-tests.index');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::post('/logout', [PageController::class, 'logout'])->name('logout');

Route::get('/coming-soon/{feature}', [PageController::class, 'comingSoon'])->name('coming-soon');

// ---- Aliases for the demo URLs baked into the original files -------------
Route::redirect('/dashboard-demo', '/dashboard');
Route::redirect('/live-class-demo', '/live-classes');
