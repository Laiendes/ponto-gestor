<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Rules\ValidCpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        $employee = $this->user()
            ->employees()
            ->findOrFail($this->route('employee'));

        return [
            'name' => ['required', 'string', 'max:255'],

            'cpf' => [
                'required',
                new ValidCpf,
                Rule::unique('users', 'cpf')->ignore($employee->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::defaults(),
            ],

            'position' => ['required', 'string', 'max:255'],

            'birth_date' => ['required', 'date', 'before:today'],

            'zip_code' => ['required', 'digits:8'],

            'street' => ['required', 'string', 'max:255'],

            'number' => ['required', 'string', 'max:20'],

            'complement' => ['nullable', 'string', 'max:255'],

            'neighborhood' => ['required', 'string', 'max:255'],

            'city' => ['required', 'string', 'max:255'],

            'state' => [
                'required',
                'string',
                'size:2',
                'regex:/^[A-Za-z]{2}$/',
            ],
        ];
    }
}
