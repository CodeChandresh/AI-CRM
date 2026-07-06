<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'inertia'], function () {
    // Public routes
    Route::get('/', function () {
        return Inertia::render('Welcome');
    });

    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    })->middleware('guest');

    Route::get('/register', function () {
        return Inertia::render('Auth/Register');
    })->middleware('guest');

    // Authenticated routes
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/meetings', function () {
            return Inertia::render('Meetings/Index');
        })->name('meetings');

        // Role-based route groups
        Route::group(['middleware' => 'role:admin'], function () {
            Route::get('/admin/dashboard', function () {
                return Inertia::render('Admin/Dashboard');
            })->name('admin.dashboard');
        });

        Route::group(['middleware' => 'role:agent'], function () {
            Route::get('/agent/dashboard', function () {
                return Inertia::render('Agent/Dashboard');
            })->name('agent.dashboard');
        });
    });
});

// API routes
Route::group(['middleware' => 'api'], function () {
    // Authentication routes
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

    // Protected API routes
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/leads', [App\Http\Controllers\LeadController::class, 'index']);
        Route::post('/leads', [App\Http\Controllers\LeadController::class, 'store']);
        Route::get('/leads/{lead}', [App\Http\Controllers\LeadController::class, 'show']);
        Route::put('/leads/{lead}', [App\Http\Controllers\LeadController::class, 'update']);
        Route::delete('/leads/{lead}', [App\Http\Controllers\LeadController::class, 'destroy']);
    });
});