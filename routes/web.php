```php
<?php

use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Employee\TimeEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::view('/dashboard', 'dashboard')
                ->name('dashboard');

            Route::resource('employees', EmployeeController::class)
                ->except(['show']);
        });

    Route::middleware('role:employee')
        ->prefix('employee')
        ->name('employee.')
        ->group(function () {
            Route::view('/dashboard', 'dashboard')
                ->name('dashboard');

            Route::controller(TimeEntryController::class)
                ->prefix('time-entries')
                ->name('time-entries.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                });
        });
});
