<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Public bus search
Route::get('/buses/search', [BusController::class, 'search'])
    ->name('buses.search');

// Authentication routes
Auth::routes();
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');

    // Bus Management
    Route::middleware('can:manage buses')
        ->prefix('buses')
        ->name('buses.')
        ->group(function () {

            Route::get('/', [BusController::class, 'index'])->name('index');
            Route::get('/create', [BusController::class, 'create'])->name('create');
            Route::post('/', [BusController::class, 'store'])->name('store');
            Route::get('/{bus}', [BusController::class, 'show'])->name('show');
            Route::get('/{bus}/edit', [BusController::class, 'edit'])->name('edit');
            Route::put('/{bus}', [BusController::class, 'update'])->name('update');
            Route::delete('/{bus}', [BusController::class, 'destroy'])->name('destroy');
        });

    // User Management
    Route::middleware('can:manage users')
        ->prefix('users')
        ->name('users.')
        ->group(function () {

            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

    // Role Management 
    Route::middleware('can:manage roles')
        ->prefix('roles')
        ->name('roles.')
        ->group(function () {

            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        });
});
