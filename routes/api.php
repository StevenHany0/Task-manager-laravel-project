<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/tasks',[TaskController::class,'index']);

// Route::post('/tasks',[TaskController::class,'store']);
// Route::get('/tasks/{id}',[TaskController::class,'show']);
// Route::put('/tasks/{id}',[TaskController::class,'update']);

// Route::delete('/tasks/{id}',[TaskController::class,'destroy']);


Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('profiles',ProfileController::class);

    Route::get('profiles/user/{id}',[UserController::class,'getprofile']);
    Route::get('user/tasks/{id}',[UserController::class,'gettasks']);

    Route::prefix('tasks')->group(function(){

        Route::apiResource('',TaskController::class);
        Route::get('/user/{id}',[TaskController::class,'getUser']);

        Route::post('/categories/{taskId}', [TaskController::class, 'addCategory']);
        Route::get('/categories/{taskId}', [TaskController::class, 'getCategories']);

        Route::get('/all', [TaskController::class, 'getAllTasks'])->middleware('isAdmin');
        Route::post('/{taskId}/favorite', [TaskController::class, 'addFavorite']);
        Route::delete('/{taskId}/favorite', [TaskController::class, 'removeFavorite']);
        Route::get('/favorites', [TaskController::class, 'getFavoriteTasks']);
    });

    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/tasks/{categoryId}', [CategoryController::class, 'getTasks']);
});
