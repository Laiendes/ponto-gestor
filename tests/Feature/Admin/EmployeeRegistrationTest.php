<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();

    $this->employeeData = [
        'name' => 'Funcionário de Teste',
        'cpf' => '52998224725',
        'email' => 'funcionario@pontogestor.test',
        'password' => 'SenhaSegura123!',
        'password_confirmation' => 'SenhaSegura123!',
        'position' => 'Desenvolvedor',
        'birth_date' => '1995-05-15',
        'zip_code' => '58000000',
        'street' => 'Rua de Teste',
        'number' => '123',
        'complement' => 'Apartamento 101',
        'neighborhood' => 'Centro',
        'city' => 'João Pessoa',
        'state' => 'PB',
    ];
});

test('administrador consegue acessar o formulário de cadastro', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.employees.create'))
        ->assertOk();
});

test('administrador consegue cadastrar um funcionário', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.employees.store'), $this->employeeData)
        ->assertRedirect(route('admin.employees.create'))
        ->assertSessionHas('success');

    $employee = User::where('email', $this->employeeData['email'])
        ->firstOrFail();

    expect($employee->role)->toBe(UserRole::Employee)
        ->and($employee->manager_id)->toBe($this->admin->id)
        ->and($employee->position)->toBe('Desenvolvedor')
        ->and(Hash::check('SenhaSegura123!', $employee->password))->toBeTrue();

    $this->assertDatabaseCount('users', 2);
});

test('funcionário é vinculado ao administrador autenticado', function () {
    $otherAdmin = User::factory()->admin()->create();

    $data = array_merge($this->employeeData, [
        'manager_id' => $otherAdmin->id,
        'role' => UserRole::Admin->value,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.employees.store'), $data)
        ->assertRedirect(route('admin.employees.create'));

    $employee = User::where('email', $data['email'])->firstOrFail();

    expect($employee->manager_id)->toBe($this->admin->id)
        ->and($employee->role)->toBe(UserRole::Employee);
});

test('funcionário não consegue cadastrar outro funcionário', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->post(route('admin.employees.store'), $this->employeeData)
        ->assertForbidden();

    $this->assertDatabaseCount('users', 2);
});

test('visitante não consegue cadastrar funcionários', function () {
    $this->post(route('admin.employees.store'), $this->employeeData)
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('users', 1);
});

test('cadastro rejeita CPF inválido', function () {
    $data = array_merge($this->employeeData, [
        'cpf' => '11111111111',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.employees.store'), $data)
        ->assertSessionHasErrors('cpf');

    $this->assertDatabaseCount('users', 1);
});

test('cadastro rejeita email duplicado', function () {
    $data = array_merge($this->employeeData, [
        'email' => $this->admin->email,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.employees.store'), $data)
        ->assertSessionHasErrors('email');

    $this->assertDatabaseCount('users', 1);
});

test('cadastro rejeita senhas que não correspondem', function () {
    $data = array_merge($this->employeeData, [
        'password_confirmation' => 'SenhaDiferente123!',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.employees.store'), $data)
        ->assertSessionHasErrors('password');

    $this->assertDatabaseCount('users', 1);
});

test('cadastro rejeita data de nascimento futura', function () {
    $data = array_merge($this->employeeData, [
        'birth_date' => now()->addYear()->toDateString(),
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.employees.store'), $data)
        ->assertSessionHasErrors('birth_date');

    $this->assertDatabaseCount('users', 1);
});

test('cadastro rejeita CEP inválido', function () {
    $data = array_merge($this->employeeData, [
        'zip_code' => '123',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.employees.store'), $data)
        ->assertSessionHasErrors('zip_code');

    $this->assertDatabaseCount('users', 1);
});
