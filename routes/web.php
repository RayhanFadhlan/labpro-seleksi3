<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;

Route::get('/', function () {
  return view('welcome');
})->name('home');

Route::get('/error', function () {
  return view('error.error');
})->name('error');


Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/browse', [FilmController::class, 'browse'])->name('browse');
Route::get('/films/{film}', [FilmController::class, 'show'])->name('films.show');

Route::get('/films/{film}/watch', [FilmController::class, 'watch'])->name('films.watch');


Route::middleware([UserMiddleware::class])->group(function () {

  Route::post('/films/{film}/buy', [FilmController::class, 'buy'])->name('films.buy');
  Route::get('/dashboard', [FilmController::class, 'dashboard'])->name('dashboard');
});

