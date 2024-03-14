<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Me
Route::get('/me', [UserController::class, 'getAuthUser'])->middleware('auth:sanctum');

//Login
Route::post('/login', [LoginController::class, 'login']);

//Auth
Route::middleware('auth:sanctum')->group(function () {
    //Logout
    Route::get('/logout', [LoginController::class, 'logout']);
    
    //Dashboard
    Route::prefix('/statistics')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
    });

    //Users
    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });

    //Article
    Route::prefix('/articles')->middleware('role:author|reviewer')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::get('/user/{id}', [ArticleController::class, 'getUserAticles']);
        Route::get('/for-review/', [ArticleController::class, 'getArticlesForReview']);
        Route::get('/{id}', [ArticleController::class, 'show']);
        Route::post('/', [ArticleController::class, 'store']);
        Route::put('/{id}', [ArticleController::class, 'update']);
        Route::delete('/{id}', [ArticleController::class, 'delete']);
    });

    //Reviews
    Route::prefix('/reviews')->middleware('role:reviewer')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::get('/{id}', [ReviewController::class, 'show']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'delete']);
    });
});
