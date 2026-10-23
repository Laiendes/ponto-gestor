<?php

use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('dashboard');
        })->name('admin.dashboard');

        Route::get('/admin/employees', [EmployeeController::class, 'index'])
            ->name('admin.employees.index');

        Route::get('/admin/employees/create', [EmployeeController::class, 'create'])
            ->name('admin.employees.create');

        Route::post('/admin/employees', [EmployeeController::class, 'store'])
            ->name('admin.employees.store');

        Route::get('/admin/employees/{employee}/edit', [EmployeeController::class, 'edit'])
            ->name('admin.employees.edit');

        Route::put('/admin/employees/{employee}', [EmployeeController::class, 'update'])
            ->name('admin.employees.update');
    });

    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/dashboard', function () {
            return view('dashboard');
        })->name('employee.dashboard');
    });
});
