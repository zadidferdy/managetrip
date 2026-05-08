<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |------------------------------------------------------------------
    | Dashboard — semua role yang sudah login bisa akses
    |------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |------------------------------------------------------------------
    | Administration — hanya Manager
    |------------------------------------------------------------------
    */
    Route::middleware('role:manager')->prefix('administrator')->group(function () {
        Route::get('/users',          [UserController::class, 'index'])->name('admin.users');
        Route::put('/users/{id}',     [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}',  [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    /*
    |------------------------------------------------------------------
    | Trip — Manager & Admin
    |------------------------------------------------------------------
    */
    Route::middleware('role:manager,admin')->prefix('transportation')->group(function () {
        Route::get('/trip',               [TripController::class, 'index'])->name('trip.index');
        Route::get('/trip/create',        [TripController::class, 'create'])->name('trip.create');
        Route::post('/trip',              [TripController::class, 'store'])->name('trip.store');
        Route::get('/trip/{id}/edit',     [TripController::class, 'edit'])->name('trip.edit');
        Route::put('/trip/{id}',          [TripController::class, 'update'])->name('trip.update');
        Route::delete('/trip/{id}',       [TripController::class, 'destroy'])->name('trip.destroy');
        Route::patch('/trip/{id}/start',  [TripController::class, 'start'])->name('trip.start');
        Route::patch('/trip/{id}/finish', [TripController::class, 'finish'])->name('trip.finish');
        Route::get('/trip/{id}/detail',   [TripController::class, 'detail'])->name('trip.detail');
        Route::get('/transportation/trip/export', [TripController::class, 'export'])->name('trip.export');
    });

    /*
    |------------------------------------------------------------------
    | Approval — Manager & Admin Transportation
    |------------------------------------------------------------------
    */
    Route::middleware('role:manager,admin_trans')->prefix('transportation')->group(function () {
        Route::get('/approval',                   [ApprovalController::class, 'index'])->name('approval.index');
        Route::patch('/approval/{id}/approve',    [ApprovalController::class, 'approve'])->name('approval.approve');
        Route::patch('/approval/{id}/reject',     [ApprovalController::class, 'reject'])->name('approval.reject');
    });

    /*
    |------------------------------------------------------------------
    | Vehicle — Manager & Admin Transportation
    |------------------------------------------------------------------
    */
    Route::middleware('role:manager,admin_trans')->prefix('transportation')->group(function () {
        Route::get('/vehicles',             [VehicleController::class, 'index'])->name('vehicle.index');
        Route::get('/vehicles/create',      [VehicleController::class, 'create'])->name('vehicle.create');
        Route::post('/vehicles',            [VehicleController::class, 'store'])->name('vehicle.store');
        Route::get('/vehicles/{id}/edit',   [VehicleController::class, 'edit'])->name('vehicle.edit');
        Route::put('/vehicles/{id}',        [VehicleController::class, 'update'])->name('vehicle.update');
        Route::delete('/vehicles/{id}',     [VehicleController::class, 'destroy'])->name('vehicle.destroy');
        Route::get('/vehicles/{id}/detail', [VehicleController::class, 'detail'])->name('vehicle.detail');
    });

});