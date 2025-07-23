<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/',[LoginController::class,'view']);
Route::post('/login',[LoginController::class,'login'])->name('login');
// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard',[DashboardController::class,'index'])->middleware('auth');

