<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::prefix('site')->group(function (){
    Route::get('login', [App\Http\Controllers\Site\LoginController::class, 'index'])->name('login');
    Route::post('login', [App\Http\Controllers\Site\LoginController::class, 'login'])->name('login');
    Route::get('logout', [App\Http\Controllers\Site\LoginController::class, 'logout'])->name('logout');
    Route::get('admin', [App\Http\Controllers\Site\AdminController::class, 'index'])->name('admin');
    Route::get('user', [App\Http\Controllers\Site\UserController::class, 'index'])->name('user');

});

Route::prefix(('project'))->group(function(){
    Route::get('table', [App\Http\Controllers\Site\ProjectController::class, 'index'])->name('table');
    Route::get('create', [App\Http\Controllers\Site\ProjectController::class, 'showForm'])->name('create.project');
    Route::post('create', [App\Http\Controllers\Site\ProjectController::class, 'store'])->name('store.project');
    Route::delete('delete/{id}', [App\Http\Controllers\Site\ProjectController::class, 'destroy'])->name('delete.project');
});

Route::prefix(('admin'))->group(function(){
    Route::get('create/user', [App\Http\Controllers\Site\UserController::class, 'showForm'])->name('create.user');
    Route::get('users', [App\Http\Controllers\Site\UserController::class, 'show'])->name('users');
    Route::get('admins', [App\Http\Controllers\Site\AdminController::class, 'show'])->name('admins');
    Route::get('create', [App\Http\Controllers\Site\AdminController::class, 'showForm'])->name('create.admin');
    Route::post('create/user', [App\Http\Controllers\Site\UserController::class, 'store'])->name('store.user');
    Route::post('create', [App\Http\Controllers\Site\AdminController::class, 'store'])->name('store.admin');
    Route::delete('delete/user/{id}', [App\Http\Controllers\Site\UserController::class, 'destroy'])->name('delete.user');
    Route::delete('delete/admin/{id}', [App\Http\Controllers\Site\AdminController::class, 'destroy'])->name('delete.admin');
});
