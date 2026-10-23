<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();

    $this->employee = User::factory()->employee()->create([
        'manager_id' => $this->admin->id,
        'cpf' => '52998224725',
        'email' => 'funcionario@exemplo.com',
    ]);

    $this->payload = [
        'name' => 'Funcionário Atualizado',
        'cpf' => '52998224725',
        'email' => 'funcionario@exemplo.com',
        'position' => 'Desenvolvedor PHP',
        'birth_date' => '1995-05-15',
        'zip_code' => '58000000',
        'street' => 'Rua das Flores',
        'number' => '123',
        'complement' => 'Apartamento 101',
        'neighborhood' => 'Centro',
        'city' => 'João Pessoa',
        'state' => 'PB',
        'password' => '',
        'password_confirmation' => '',
    ];
});

it('administrador consegue acessar o formulário de edição', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.employees.edit', $this->employee))
        ->assertOk()
        ->assertViewIs('admin.employees.edit');
});

it('formulário apresenta os dados atuais do funcionário', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.employees.edit', $this->employee))
        ->assertOk()
        ->assertSee($this->employee->name)
        ->assertSee($this->employee->email)
        ->assertSee($this->employee->cpf);
});

it('administrador consegue atualizar os dados de seu funcionário', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $this->payload)
        ->assertRedirect(route('admin.employees.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $this->employee->id,
        'name' => 'Funcionário Atualizado',
        'position' => 'Desenvolvedor PHP',
        'manager_id' => $this->admin->id,
    ]);
});

it('funcionário pode manter seu CPF e email atuais', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $this->payload)
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('users', [
        'id' => $this->employee->id,
        'cpf' => '52998224725',
        'email' => 'funcionario@exemplo.com',
    ]);
});

it('senha permanece inalterada quando não é informada', function () {
    $originalPassword = $this->employee->password;

    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $this->payload)
        ->assertSessionHasNoErrors();

    expect($this->employee->fresh()->password)->toBe($originalPassword);
});

it('administrador consegue definir uma nova senha', function () {
    $payload = array_merge($this->payload, [
        'password' => 'NovaSenhaSegura123!',
        'password_confirmation' => 'NovaSenhaSegura123!',
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $payload)
        ->assertSessionHasNoErrors();

    expect(Hash::check('NovaSenhaSegura123!', $this->employee->fresh()->password))
        ->toBeTrue();
});

it('administrador não consegue editar funcionário de outro administrador', function () {
    $otherAdmin = User::factory()->admin()->create();

    $this->actingAs($otherAdmin)
        ->get(route('admin.employees.edit', $this->employee))
        ->assertNotFound();

    $this->actingAs($otherAdmin)
        ->put(route('admin.employees.update', $this->employee), $this->payload)
        ->assertNotFound();
});

it('funcionário comum não consegue acessar a edição', function () {
    $this->actingAs($this->employee)
        ->get(route('admin.employees.edit', $this->employee))
        ->assertForbidden();

    $this->actingAs($this->employee)
        ->put(route('admin.employees.update', $this->employee), $this->payload)
        ->assertForbidden();
});

it('visitante é redirecionado para o login', function () {
    $this->get(route('admin.employees.edit', $this->employee))
        ->assertRedirect(route('login'));

    $this->put(route('admin.employees.update', $this->employee), $this->payload)
        ->assertRedirect(route('login'));
});

it('edição rejeita CPF duplicado', function () {
    User::factory()->employee()->create([
        'cpf' => '11144477735',
    ]);

    $payload = array_merge($this->payload, [
        'cpf' => '11144477735',
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $payload)
        ->assertSessionHasErrors('cpf');
});

it('edição rejeita email duplicado', function () {
    $otherEmployee = User::factory()->employee()->create([
        'email' => 'outro@exemplo.com',
    ]);

    $payload = array_merge($this->payload, [
        'email' => $otherEmployee->email,
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $payload)
        ->assertSessionHasErrors('email');
});

it('edição não permite alterar perfil ou administrador responsável', function () {
    $otherAdmin = User::factory()->admin()->create();

    $payload = array_merge($this->payload, [
        'role' => UserRole::Admin->value,
        'manager_id' => $otherAdmin->id,
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $payload)
        ->assertSessionHasNoErrors();

    $employee = $this->employee->fresh();

    expect($employee->role)->toBe(UserRole::Employee)
        ->and($employee->manager_id)->toBe($this->admin->id);
});

it('funcionário excluído logicamente não pode ser editado', function () {
    $this->employee->delete();

    $this->actingAs($this->admin)
        ->get(route('admin.employees.edit', $this->employee))
        ->assertNotFound();

    $this->actingAs($this->admin)
        ->put(route('admin.employees.update', $this->employee), $this->payload)
        ->assertNotFound();
});
