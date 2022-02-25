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
    Route::put('user/{id}',[App\Http\Controllers\Api\AdminController::class,'update']);
    Route::delete('delete/user/{id}',[App\Http\Controllers\Api\AdminController::class,'destroy']);
    Route::middleware('authJwt')->get('projects/all',[App\Http\Controllers\Api\AdminController::class,'allProjects']);
});

Route::group([
    'middleware' => 'authJwt',
    'prefix' => 'users'
],function (){
    Route::get('/', [App\Http\Controllers\Api\UserController::class, 'index']);
    Route::get('tasks/project/{id}', [App\Http\Controllers\Api\TaskController::class, 'index']);
    Route::post('createproject', [App\Http\Controllers\Api\ProjectController::class, 'store']);
    Route::post('createtask/{id}', [App\Http\Controllers\Api\TaskController::class, 'store']);
    Route::put('updateproject/{id}', [App\Http\Controllers\Api\ProjectController::class, 'update']);
    Route::put('updatetask/{id}', [App\Http\Controllers\Api\TaskController::class, 'update']);
    Route::delete('deleteproject/{id}', [App\Http\Controllers\Api\ProjectController::class, 'destroy']);
    Route::delete('deletetask/{id}', [App\Http\Controllers\Api\TaskController::class, 'destroy']);
});
