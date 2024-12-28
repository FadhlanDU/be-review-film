<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CastController;
use App\Http\Controllers\API\GenresController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\CastMovieController;

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


Route::prefix('v1')->group(function () {
    Route::apiResource('casts', CastController::class);
    Route::apiResource('genres', GenresController::class);
    Route::apiResource('movies', MovieController::class);
    Route::apiResource('role', RoleController::class);
    Route::apiResource('castsmovies', CastMovieController::class);

    Route::prefix('auth')->group(function() {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/me', [AuthController::class, 'currentUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/update', [AuthController::class, 'update']);
        Route::post('/verifikasi-akun', [AuthController::class, 'verifikasi']);
        Route::post('/generate-otp-code', [AuthController::class, 'generateotp']);
    })->middleware('api');

    Route::post('/profile', [ProfileController::class, 'storeupdate']);
    Route::post('/review', [ReviewController::class, 'storeupdate']);
});
