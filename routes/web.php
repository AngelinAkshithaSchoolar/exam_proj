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

// K-12 / exam-prep Learning Dashboard. Sits directly under Dashboard in the
// sidebar. Self-contained view — every variable in it has a fallback, so
// Route::view is enough until real data is wired in from a controller.
Route::view('/learning-dashboard', 'learning-dashboard')->name('learning-dashboard');

Route::view('/live-classes', 'live-classes')->name('live-classes.index');

Route::view('/mira', 'mira')->name('mira.index');

Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');

Route::post('/practice', [PracticeController::class, 'api'])->name('practice.api');

// Mock History. URL matches the sidebar label and the route name; the view
// itself stays at resources/views/mock-tests/history.blade.php.
Route::view('/mock-history', 'mock-tests.history')->name('mock-history.index');

// Old nested URL kept alive so any bookmark still lands in the right place.
Route::redirect('/mock-tests/history', '/mock-history');

// ---- RRB ALP CBT 1 mock test ---------------------------------------------
// The whole test — instructions, question player, result and answer review —
// lives in resources/views/alp-cbt1.blade.php. Nothing else to wire up.
Route::view('/alp-cbt1', 'alp-cbt1')->name('alp-cbt1');

// ---- Supporting pages ----------------------------------------------------
Route::get('/my-tests', [PageController::class, 'mockTests'])->name('mock-tests.index');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::post('/logout', [PageController::class, 'logout'])->name('logout');

Route::get('/coming-soon/{feature}', [PageController::class, 'comingSoon'])->name('coming-soon');


// ---- Aliases for the demo URLs baked into the original files -------------
Route::redirect('/dashboard-demo', '/dashboard');
Route::redirect('/live-class-demo', '/live-classes');

Route::view('/performance', 'performance', ['active' => 'performance'])->name('performance');