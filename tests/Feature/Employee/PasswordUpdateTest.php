<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->employee = User::factory()->employee()->create([
        'password' => 'SenhaAtual123!',
    ]);
});

test('funcionário consegue acessar o formulário de alteração de senha', function () {
    $this->actingAs($this->employee)
        ->get(route('employee.password.edit'))
        ->assertOk()
        ->assertSee('Alterar senha');
});

test('funcionário consegue alterar a própria senha', function () {
    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaAtual123!',
            'password' => 'NovaSenha123!',
            'password_confirmation' => 'NovaSenha123!',
        ])
        ->assertRedirect(route('employee.password.edit'))
        ->assertSessionHas('success');

    expect(Hash::check('NovaSenha123!', $this->employee->fresh()->password))
        ->toBeTrue();
});

test('nova senha é armazenada utilizando hash', function () {
    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaAtual123!',
            'password' => 'NovaSenha123!',
            'password_confirmation' => 'NovaSenha123!',
        ])
        ->assertSessionHasNoErrors();

    expect($this->employee->fresh()->password)
        ->not->toBe('NovaSenha123!');
});

test('funcionário não consegue alterar senha informando senha atual incorreta', function () {
    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaIncorreta123!',
            'password' => 'NovaSenha123!',
            'password_confirmation' => 'NovaSenha123!',
        ])
        ->assertSessionHasErrors('current_password');

    expect(Hash::check('SenhaAtual123!', $this->employee->fresh()->password))
        ->toBeTrue();
});

test('funcionário não consegue alterar senha sem confirmação correta', function () {
    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaAtual123!',
            'password' => 'NovaSenha123!',
            'password_confirmation' => 'ConfirmacaoDiferente123!',
        ])
        ->assertSessionHasErrors('password');

    expect(Hash::check('SenhaAtual123!', $this->employee->fresh()->password))
        ->toBeTrue();
});

test('funcionário não consegue reutilizar a senha atual', function () {
    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaAtual123!',
            'password' => 'SenhaAtual123!',
            'password_confirmation' => 'SenhaAtual123!',
        ])
        ->assertSessionHasErrors('password');
});

test('funcionário não consegue definir senha que descumpre a política de segurança', function () {
    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaAtual123!',
            'password' => '123',
            'password_confirmation' => '123',
        ])
        ->assertSessionHasErrors('password');
});

test('funcionário não consegue alterar senha sem informar senha atual', function () {
    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'password' => 'NovaSenha123!',
            'password_confirmation' => 'NovaSenha123!',
        ])
        ->assertSessionHasErrors('current_password');
});

test('administrador não consegue acessar formulário de alteração de senha do funcionário', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('employee.password.edit'))
        ->assertForbidden();
});

test('administrador não consegue alterar senha pela área do funcionário', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaAtual123!',
            'password' => 'NovaSenha123!',
            'password_confirmation' => 'NovaSenha123!',
        ])
        ->assertForbidden();
});

test('visitante não consegue acessar formulário de alteração de senha', function () {
    $this->get(route('employee.password.edit'))
        ->assertRedirect(route('login'));
});

test('visitante não consegue alterar senha', function () {
    $this->put(route('employee.password.update'), [
        'current_password' => 'SenhaAtual123!',
        'password' => 'NovaSenha123!',
        'password_confirmation' => 'NovaSenha123!',
    ])
        ->assertRedirect(route('login'));
});

test('alteração de senha não modifica a senha de outro funcionário', function () {
    $otherEmployee = User::factory()->employee()->create([
        'password' => 'OutraSenha123!',
    ]);

    $this->actingAs($this->employee)
        ->put(route('employee.password.update'), [
            'current_password' => 'SenhaAtual123!',
            'password' => 'NovaSenha123!',
            'password_confirmation' => 'NovaSenha123!',
        ])
        ->assertSessionHasNoErrors();

    expect(Hash::check('NovaSenha123!', $this->employee->fresh()->password))
        ->toBeTrue();

    expect(Hash::check('OutraSenha123!', $otherEmployee->fresh()->password))
        ->toBeTrue();
});
