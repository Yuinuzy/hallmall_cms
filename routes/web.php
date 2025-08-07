<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MerchantKategoriController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MerchantKategoriSubController;


Route::get('/', [LoginController::class, 'view']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'view'])->name('login.form');
// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

//Forgot Password
Route::get('password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');



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
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/json', [RoleController::class, 'json']);
    Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/{id}/update', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/{id}/delete', [RoleController::class, 'destroy'])->name('role.destroy');
    Route::get('/{id}/edit-inline', [RoleController::class, 'editInline'])->name('role.edit-inline');
    Route::get('/{id}/detail', [RoleController::class, 'show'])->name('role.show');
    Route::post('/{id}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('role.syncPermissions');
    Route::get('/{id}/permissions/json', [RoleController::class, 'permissionsJson'])->name('role.permissions.json');
    Route::get('/{id}/assign-permissions', [RoleController::class, 'assignPermissionsPage'])->name('roles.assign.permissions');
    Route::post('/{id}/assign-permissions', [RoleController::class, 'assignPermissionsSave'])->name('roles.assign.permissions.save');
    Route::get('/{id}/add-permissions', [RoleController::class, 'addPermissionsPage'])->name('roles.add.permissions');
    Route::post('/{id}/add-permissions', [RoleController::class, 'addPermissionsSave'])->name('roles.add.permissions.save');
    Route::post('/{id}/revoke-permission', [RoleController::class, 'revokePermission'])->name('roles.revoke.permission');
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
    Route::get("/", [PermissionController::class, "index"])->name('permission.index');
    Route::get("/create", [PermissionController::class, "create"]);
    Route::post("/store", [PermissionController::class, "store"]);
    Route::get("/{id}/get-data", [PermissionController::class, "get_data"]);
    Route::put("/{id}", [PermissionController::class, "update"]);
    Route::delete("/{id}/delete", [PermissionController::class, "destroy"]);
    Route::post("/{id}/change-status", [PermissionController::class, "change_status"]);
    Route::get("/json", [PermissionController::class, "json"]);
});

Route::prefix("merchant_kategori")->group(function () {
    Route::get("/", [MerchantKategoriController::class, "index"])->name('merchant_kategori.index');
    Route::get('/json', [MerchantKategoriController::class, 'json']);
    Route::get('/create', [MerchantKategoriController::class, 'create'])->name('merchant_kategori.create');
    Route::post('/store', [MerchantKategoriController::class, 'store'])->name('merchant_kategori.store');
    Route::get('/{id}/get_data', [MerchantKategoriController::class, 'getData']);
    Route::get("{id}/edit", [MerchantKategoriController::class, 'edit'])->name('merchant_kategori.edit');
    Route::put('/{id}', [MerchantKategoriController::class, 'update']);
    Route::delete('/{id}/delete', [MerchantKategoriController::class, 'destroy']);
    Route::get('/{id}/detail', [MerchantKategoriController::class, 'show'])->name('merchant_kategori.show');
});

Route::prefix('sub_kategori')->middleware('auth')->group(function () {
    Route::get('/', [MerchantKategoriSubController::class, 'index'])->name('sub_kategori.index');
    Route::get('/json', [MerchantKategoriSubController::class, 'json'])->name('sub_kategori.json');
    Route::get('/{id}/edit', [MerchantKategoriSubController::class, 'edit'])->name('sub_kategori.edit');
    Route::get('/{id}/data', [MerchantKategoriSubController::class, 'getData']);
    Route::put('/{id}', [MerchantKategoriSubController::class, 'update'])->name('sub_kategori.update');
    Route::get('/{id}/detail', [MerchantKategoriSubController::class, 'detail'])->name('sub_kategori.detail');
    Route::get('/create', [MerchantKategoriSubController::class, 'create'])->name('sub_kategori.create');
    Route::post("/store", [MerchantKategoriSubController::class, "store"])->name('sub_kategori.store');
    Route::delete('/{id}/delete', [MerchantKategoriSubController::class, 'destroy'])->name('sub_kategori.destroy');
});



