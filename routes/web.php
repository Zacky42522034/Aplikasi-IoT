<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\DashboardController;

//login & Register
Route::get('/', function () {
    return redirect('login');
});

// Route::get('/login', function () {
//     return view('Auth.Login')->name('login');
// });

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/postReg', [AuthController::class, 'postReg'])->name('postReg');
Route::post('/postLog', [AuthController::class, 'postLog'])->name('postLog');

//Dashboard
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/dashboardChart', [DashboardController::class, 'dashboardChart'])->name('dashboardChart');
 
//Perangkat 
Route::get('/devices', [DevicesController::class, 'pairedDevices'])->name('pairedDevices')->middleware('auth');
Route::post('/pairs', [DevicesController::class, 'pairs'])->name('pairs');
Route::get('/detailDevices/{id}', [DevicesController::class, 'detailDevices'])->name('detailDevices')->middleware('auth');
// Route::get('/search', [DevicesController::class, 'searchLocation'])->name('searchLocation');
 
// Fallback route untuk menangani slug atau halaman yang tidak ditemukan
Route::fallback(function () {
    return response()->view('App.404', [], 404);
})->name('fallback');



//Tambahkan Perangkat
Route::get('/pairings', function () {
    return view('App.Pairing');
});


Route::get('/devicesss', function () {
    return view('form');
});


Route::post('/process', [DeviceController::class, 'process'])->name('process');
Route::get('/check-session', [DeviceController::class, 'checkSession']);
Route::delete('/delete-device/{id}', [DeviceController::class, 'deleteDevice']);




