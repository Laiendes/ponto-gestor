<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\PasswordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function __construct(
        private readonly PasswordService $passwordService
    ) {}

    public function edit(): View
    {
        return view('employee.password.edit');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->passwordService->update(
            $request->user(),
            $validated['password']
        );

        return redirect()
            ->route('employee.password.edit')
            ->with('success', 'Senha alterada com sucesso.');
    }
}
