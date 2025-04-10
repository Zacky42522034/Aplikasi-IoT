<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\DashboardController;

//login & Register
Route::get('/', function () {
    return view('Auth.Login');
});

Route::post('/postReg', [AuthController::class, 'postReg'])->name('postReg');
Route::post('/postLog', [AuthController::class, 'postLog'])->name('postLog');

//Dashboard
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
 
//Perangkat 
Route::get('/devices', [DevicesController::class, 'pairedDevices'])->name('pairedDevices');
Route::post('/pairs', [DevicesController::class, 'pairs'])->name('pairs');
Route::get('/detailDevices/{id}', [DevicesController::class, 'detailDevices'])->name('detailDevices');
// Route::get('/search', [DevicesController::class, 'searchLocation'])->name('searchLocation');

 
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




