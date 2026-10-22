<?php

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminData = [
        'ADMIN_NAME' => 'Administrador Inicial',
        'ADMIN_EMAIL' => 'admin@pontogestor.test',
        'ADMIN_CPF' => '52998224725',
        'ADMIN_PASSWORD' => 'SenhaSegura123!',
    ];

    foreach ($this->adminData as $key => $value) {
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
});

afterEach(function () {
    foreach (array_keys($this->adminData) as $key) {
        unset($_ENV[$key], $_SERVER[$key]);
    }
});

test('cria o administrador inicial com o perfil correto', function () {
    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', 'admin@pontogestor.test')->firstOrFail();

    expect($admin->role)->toBe(UserRole::Admin)
        ->and($admin->manager_id)->toBeNull()
        ->and($admin->cpf)->toBe('52998224725');

    $this->assertDatabaseCount('users', 1);
});

test('armazena a senha do administrador utilizando hash', function () {
    $this->seed(AdminUserSeeder::class);

    $admin = User::firstOrFail();

    expect($admin->password)->not->toBe('SenhaSegura123!')
        ->and(Hash::check('SenhaSegura123!', $admin->password))->toBeTrue();
});

test('não duplica o administrador quando o seeder é executado novamente', function () {
    $this->seed(AdminUserSeeder::class);
    $this->seed(AdminUserSeeder::class);

    $this->assertDatabaseCount('users', 1);
});

test('rejeita email já cadastrado para outro usuário', function () {
    User::factory()->create([
        'email' => 'admin@pontogestor.test',
    ]);

    expect(fn () => $this->seed(AdminUserSeeder::class))
        ->toThrow(RuntimeException::class);

    $this->assertDatabaseCount('users', 1);
});

test('rejeita CPF já cadastrado para outro usuário', function () {
    User::factory()->create([
        'cpf' => '52998224725',
    ]);

    expect(fn () => $this->seed(AdminUserSeeder::class))
        ->toThrow(RuntimeException::class);

    $this->assertDatabaseCount('users', 1);
});

test('rejeita senha administrativa com menos de doze caracteres', function () {
    $_ENV['ADMIN_PASSWORD'] = '123456';
    $_SERVER['ADMIN_PASSWORD'] = '123456';

    expect(fn () => $this->seed(AdminUserSeeder::class))
        ->toThrow(ValidationException::class);

    $this->assertDatabaseCount('users', 0);
});

test('rejeita CPF com dígitos verificadores inválidos', function () {
    $_ENV['ADMIN_CPF'] = '52998224726';
    $_SERVER['ADMIN_CPF'] = '52998224726';

    expect(fn () => $this->seed(AdminUserSeeder::class))
        ->toThrow(ValidationException::class);

    $this->assertDatabaseCount('users', 0);
});

test('rejeita CPF formado por números repetidos', function () {
    $_ENV['ADMIN_CPF'] = '11111111111';
    $_SERVER['ADMIN_CPF'] = '11111111111';

    expect(fn () => $this->seed(AdminUserSeeder::class))
        ->toThrow(ValidationException::class);

    $this->assertDatabaseCount('users', 0);
});
