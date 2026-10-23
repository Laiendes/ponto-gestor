<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function create(): View
    {
        return view('admin.employees.create');
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = $request->user()
            ->employees()
            ->make($request->validated());

        $employee->role = UserRole::Employee;

        $employee->save();

        return redirect()
            ->route('admin.employees.create')
            ->with('success', 'Funcionário cadastrado com sucesso.');
    }
}
