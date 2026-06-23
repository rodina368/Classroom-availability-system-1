<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassroomController;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminClassroomController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

Route::get('/api/classrooms', [ClassroomController::class, 'index'])->name('api.classrooms.index');

Route::middleware('auth')->group(function () {
    Route::get('/classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show'])->name('classrooms.show');

    Route::post('/book', [BookingController::class, 'store'])->name('bookings.store');
    
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    
    Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');
    Route::post('/favourites/{classroom}', [FavouriteController::class, 'store'])->name('favourites.store');
    Route::delete('/favourites/{classroom}', [FavouriteController::class, 'destroy'])->name('favourites.destroy');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/classrooms', [AdminClassroomController::class, 'index'])->name('classrooms.index');
        Route::get('/classrooms/create', [AdminClassroomController::class, 'create'])->name('classrooms.create');
        Route::post('/classrooms', [AdminClassroomController::class, 'store'])->name('classrooms.store');
        Route::get('/classrooms/{classroom}/edit', [AdminClassroomController::class, 'edit'])->name('classrooms.edit');
        Route::put('/classrooms/{classroom}', [AdminClassroomController::class, 'update'])->name('classrooms.update');
        Route::delete('/classrooms/{classroom}', [AdminClassroomController::class, 'destroy'])->name('classrooms.destroy');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    });
});

Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
