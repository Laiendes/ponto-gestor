<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('administrador consegue acessar a área administrativa', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('administrador não consegue acessar a área de funcionários', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('employee.dashboard'))
        ->assertForbidden();
});

test('funcionário consegue acessar a área de funcionários', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('employee.dashboard'))
        ->assertOk();
});

test('funcionário não consegue acessar a área administrativa', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('visitante não consegue acessar a área administrativa', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

test('visitante não consegue acessar a área de funcionários', function () {
    $this->get(route('employee.dashboard'))
        ->assertRedirect(route('login'));
});
