<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/register', 'register')->name('register');
Route::post('/register', [RegisterController::class, 'register']);
