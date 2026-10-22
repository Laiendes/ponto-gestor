<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Rules\ValidCpf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'cpf' => env('ADMIN_CPF'),
            'password' => env('ADMIN_PASSWORD'),
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'cpf' => ['required', new ValidCpf],
            'password' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $existingUser = User::withTrashed()
            ->where('email', $data['email'])
            ->orWhere('cpf', $data['cpf'])
            ->first();

        if ($existingUser) {
            if (
                $existingUser->email === $data['email']
                && $existingUser->cpf === $data['cpf']
                && $existingUser->role === UserRole::Admin
                && $existingUser->deleted_at === null
            ) {
                $this->command?->info('O administrador inicial já está cadastrado.');

                return;
            }

            throw new RuntimeException(
                'Já existe um usuário com o e-mail ou CPF informado.'
            );
        }

        $admin = new User;

        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->cpf = $data['cpf'];
        $admin->password = $data['password'];
        $admin->role = UserRole::Admin;
        $admin->manager_id = null;

        $admin->save();

        $this->command?->info('Administrador inicial criado com sucesso.');
    }
}
