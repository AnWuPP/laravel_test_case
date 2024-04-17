<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/event/{id}', [EventController::class, 'view'])->name('event');
Route::get('/event/participate/{id}', [EventController::class, 'participate'])->name('event.participate');
Route::get('/user/{id}', [UserController::class, 'view'])->name('user');

require __DIR__.'/auth.php';
