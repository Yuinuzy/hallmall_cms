<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;


Route::get('/', [LoginController::class, 'view']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'view'])->name('login.form');
// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'userIndex'])->name('users.index');
    Route::get('/json', [UserController::class, 'json']);
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{id}/get_data', [UserController::class, 'get_data']);
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}/delete', [UserController::class, 'destroy']);
});

Route::prefix('role')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('role.index');
    Route::get('/json', [RoleController::class, 'json']);
    Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/{id}/update', [RoleController::class, 'updateRole'])->name('role.update');
});

Route::get('/admin/dashboard', function () {
    return 'Ini halaman admin';
})->middleware('role:admin');

Route::get('/post/publish', function () {
    return 'Halaman publish post';
})->middleware('permission:publish post');

Route::get('/editor', function () {
    return 'Editor Area';
})->middleware(['role:editor', 'permission:edit post']);


Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/users', function () {
        return 'Manajemen User';
    });

    Route::get('/admin/settings', function () {
        return 'Pengaturan';
    });
});

Route::prefix("permission")->group(function () {
    Route::get("/", [PermissionController::class, "index"]);
    Route::get("/create", [PermissionController::class, "create"]);
    Route::post("/store", [PermissionController::class, "store"]);
    Route::get("/{id}/get-data", [PermissionController::class, "get_data"]);
    Route::put("/{id}", [PermissionController::class, "update"]);
    Route::delete("/{id}/delete", [PermissionController::class, "destroy"]);
    Route::post("/{id}/change-status", [PermissionController::class, "change_status"]);

    Route::get("/json", [PermissionController::class, "json"]);
});
