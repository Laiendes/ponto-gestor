<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = request()
            ->user()
            ->employees()
            ->latest()
            ->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }

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

    public function edit(int $employee): View
    {
        $employee = request()
            ->user()
            ->employees()
            ->findOrFail($employee);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, int $employee): RedirectResponse
    {
        $employee = $request
            ->user()
            ->employees()
            ->findOrFail($employee);

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $employee->update($data);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Funcionário atualizado com sucesso.');
    }

    public function destroy(int $employee): RedirectResponse
    {
        $employee = request()
            ->user()
            ->employees()
            ->findOrFail($employee);

        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Funcionário excluído com sucesso.');
    }
}
