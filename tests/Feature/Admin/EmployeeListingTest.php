<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('administrador consegue acessar a listagem de funcionários', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.employees.index'))
        ->assertOk()
        ->assertViewIs('admin.employees.index');
});

test('administrador visualiza somente seus funcionários', function () {
    $otherAdmin = User::factory()->admin()->create();

    $myEmployee = User::factory()->employee()->create([
        'manager_id' => $this->admin->id,
        'name' => 'Funcionário do Administrador',
    ]);

    $otherEmployee = User::factory()->employee()->create([
        'manager_id' => $otherAdmin->id,
        'name' => 'Funcionário de Outro Administrador',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.employees.index'))
        ->assertOk()
        ->assertSeeText($myEmployee->name)
        ->assertDontSeeText($otherEmployee->name);
});

test('administrador visualiza mensagem quando não possui funcionários', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.employees.index'))
        ->assertOk()
        ->assertSeeText('Nenhum funcionário cadastrado.');
});

test('listagem apresenta dez funcionários por página', function () {
    User::factory()
        ->employee()
        ->count(15)
        ->create([
            'manager_id' => $this->admin->id,
        ]);

    $this->actingAs($this->admin)
        ->get(route('admin.employees.index'))
        ->assertOk()
        ->assertViewHas('employees', function ($employees) {
            return $employees->count() === 10
                && $employees->total() === 15
                && $employees->lastPage() === 2;
        });
});

test('segunda página apresenta os funcionários restantes', function () {
    User::factory()
        ->employee()
        ->count(15)
        ->create([
            'manager_id' => $this->admin->id,
        ]);

    $this->actingAs($this->admin)
        ->get(route('admin.employees.index', ['page' => 2]))
        ->assertOk()
        ->assertViewHas('employees', function ($employees) {
            return $employees->count() === 5
                && $employees->currentPage() === 2;
        });
});

test('funcionários excluídos logicamente não aparecem na listagem', function () {
    $activeEmployee = User::factory()->employee()->create([
        'manager_id' => $this->admin->id,
        'name' => 'Funcionário Ativo',
    ]);

    $deletedEmployee = User::factory()->employee()->create([
        'manager_id' => $this->admin->id,
        'name' => 'Funcionário Excluído',
    ]);

    $deletedEmployee->delete();

    $this->actingAs($this->admin)
        ->get(route('admin.employees.index'))
        ->assertOk()
        ->assertSeeText($activeEmployee->name)
        ->assertDontSeeText($deletedEmployee->name);
});

test('funcionário não consegue acessar a listagem administrativa', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('admin.employees.index'))
        ->assertForbidden();
});

test('visitante não consegue acessar a listagem administrativa', function () {
    $this->get(route('admin.employees.index'))
        ->assertRedirect(route('login'));
});
