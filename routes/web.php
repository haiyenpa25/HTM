<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

// Route cho đăng nhập
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/trung-lao', function () {
    return view('trung-lao');
})->middleware('auth')->name('trung-lao');

Route::get('/thanh-nien', function () {
    return view('thanh-nien');
})->middleware('auth')->name('thanh-nien');

// Route cho phân quyền vai trò
Route::get('/roles/permissions', [RoleController::class, 'permissions'])->name('roles.permissions')->middleware('auth');
Route::post('/roles/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update')->middleware('auth');

// Route cho phân quyền người dùng
Route::get('/users/permissions', [UserController::class, 'permissions'])->name('users.permissions')->middleware('auth');
Route::post('/users/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update')->middleware('auth');

// Route cho quản lý vai trò
Route::resource('roles', RoleController::class)->middleware('auth');

// Route cho quản lý quyền
Route::resource('permissions', PermissionController::class)->middleware('auth');

// Route cho quản lý người dùng
Route::resource('users', UserController::class)->middleware('auth');
