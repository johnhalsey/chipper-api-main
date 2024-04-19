<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostFavoriteController;

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

Route::post('register', RegisterController::class)->name('register');
Route::post('login', LoginController::class)->name('login');
Route::get('posts', [PostController::class, 'index'])->name('posts.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('session', SessionController::class)->name('session');
    Route::post('logout', LogoutController::class)->name('logout');
    Route::apiResource('posts', PostController::class, ['except' => ['index']]);
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('posts/{post}/favorite', [PostFavoriteController::class, 'store'])->name('posts.favorites.store');
    Route::delete('posts/{post}/favorite', [PostFavoriteController::class, 'destroy'])->name('posts.favorites.destroy');
    Route::post('users/{user}/favorite', [UserFavoriteController::class, 'store'])->name('users.favorites.store');
    Route::delete('users/{user}/favorite', [UserFavoriteController::class, 'store'])->name('users.favorites.store');


});
