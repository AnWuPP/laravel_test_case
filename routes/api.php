<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/event/create', [EventController::class, 'create']);
Route::get('/event/isParticipant', [EventController::class, 'isParticipant']);
Route::post('/event/participate', [EventController::class, 'participate']);
Route::post('/event/unParticipate', [EventController::class, 'unParticipate']);
Route::get('/events', [EventController::class, 'index'])->name('api.events');
Route::get('/events/participants', [EventController::class, 'participants'])->name('api.events.participants');
