<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('admin')->group(function (){
    Route::get('/', [App\Http\Controllers\Api\AdminController::class, 'index']);
    Route::get('user/{id}',[App\Http\Controllers\Api\AdminController::class,'show']);
    Route::post('create/user',[App\Http\Controllers\Api\AdminController::class,'store']);
    Route::put('givepermission/user/{id}',[App\Http\Controllers\Api\AdminController::class,'givePermission']);
    Route::put('cancelpermission/user/{id}',[App\Http\Controllers\Api\AdminController::class,'cancelPermission']);
    Route::put('admin/{id}',[App\Http\Controllers\Api\AdminController::class,'update']);
    Route::delete('delete/user/{id}',[App\Http\Controllers\Api\AdminController::class,'destroy']);
    Route::middleware('authJwt')->get('projects/all',[App\Http\Controllers\Api\AdminController::class,'allProjects']);
});

Route::group([
    'middleware' => 'authJwt:payload',
    'prefix' => 'users'
],function (){
    Route::get('/', [App\Http\Controllers\Api\UserController::class, 'index']);
});

Route::group([
    'middleware' => 'authJwt',
    'prefix' => 'projects'
],function (){
    Route::get('/', [App\Http\Controllers\Api\ProjectsController::class, 'index']);
});
