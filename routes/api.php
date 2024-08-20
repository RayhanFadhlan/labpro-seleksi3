<?php

use App\Http\Controllers\Api\FilmApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Middleware\AdminMidddleware;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;




// FILM
Route::middleware([AdminMidddleware::class])->group(function () {


    // FILM
    Route::post('/films', [FilmApiController::class, 'store']);
    Route::get('/films', [FilmApiController::class, 'index']);
    Route::get('/films/{id}', [FilmApiController::class, 'show']);
    Route::put('/films/{id}', [FilmApiController::class, 'update']);
    Route::delete('/films/{id}', [FilmApiController::class, 'destroy']);


    // AUTH


    // USER
    Route::get('/self', [UserApiController::class, 'self']);
    Route::get('/users', [UserApiController::class, 'index']);
    Route::get('/users/{id}', [UserApiController::class, 'show']);
    Route::post('/users/{id}/balance', [UserApiController::class, 'topup']);
    Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
});


Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);






