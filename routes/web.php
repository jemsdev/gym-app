<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('branches', BranchController::class)->except(['show']);

        Route::resource('members', 'App\Http\Controllers\Admin\MemberController')->except(['show']);

        Route::get('bookings', 'App\Http\Controllers\Admin\BookingController@index')->name('bookings.index');
        Route::get('bookings/create', 'App\Http\Controllers\Admin\BookingController@create')->name('bookings.create');
        Route::post('bookings', 'App\Http\Controllers\Admin\BookingController@store')->name('bookings.store');
        Route::put('bookings/{booking}/status', 'App\Http\Controllers\Admin\BookingController@updateStatus')->name('bookings.updateStatus');
        Route::post('bookings/{booking}/checkin', 'App\Http\Controllers\Admin\BookingController@checkin')->name('bookings.checkin');

        Route::get('checkins', 'App\Http\Controllers\Admin\CheckinController@index')->name('checkins.index');
        Route::post('checkins', 'App\Http\Controllers\Admin\CheckinController@store')->name('checkins.store');
    });

require __DIR__.'/auth.php';
