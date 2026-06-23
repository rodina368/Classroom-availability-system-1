<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/users', function () {
    return response()->json(\App\Models\User::all());
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
