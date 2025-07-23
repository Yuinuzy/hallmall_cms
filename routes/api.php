<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix("user")->middleware(["auth:api","role:Super Admin"])->group(function(){
    Route::get("/",[UserController::class, 'index']);
    Route::get("/table",[UserController::class, 'table']);
    Route::get("/{id}",[UserController::class, 'find']);
    Route::post('/add', [UserController::class, 'post']);
    Route::patch('/{id}/edit', [UserController::class, 'update']);
    Route::delete('/{id}/delete', [UserController::class, 'deletes']);
});

