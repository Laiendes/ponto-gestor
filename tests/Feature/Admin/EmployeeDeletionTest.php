<?php

use App\Enums\TimeEntryType;
use App\Enums\UserRole;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();

    $this->employee = User::factory()->employee()->create([
        'manager_id' => $this->admin->id,
    ]);
});

it('administrador consegue excluir logicamente seu funcionário', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertRedirect(route('admin.employees.index'))
        ->assertSessionHas('success', 'Funcionário excluído com sucesso.');

    $this->assertSoftDeleted('users', [
        'id' => $this->employee->id,
    ]);
});

it('funcionário excluído permanece armazenado no banco de dados', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertRedirect(route('admin.employees.index'));

    $this->assertDatabaseHas('users', [
        'id' => $this->employee->id,
        'manager_id' => $this->admin->id,
    ]);

    expect(User::withTrashed()->find($this->employee->id))->not->toBeNull();
});

it('funcionário excluído não aparece na listagem', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertRedirect(route('admin.employees.index'));

    $this->get(route('admin.employees.index'))
        ->assertOk()
        ->assertDontSee($this->employee->email);
});

it('administrador não consegue excluir funcionário de outro administrador', function () {
    $otherAdmin = User::factory()->admin()->create();

    $this->actingAs($otherAdmin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertNotFound();

    $this->assertNotSoftDeleted('users', [
        'id' => $this->employee->id,
    ]);
});

it('funcionário comum não consegue excluir funcionários', function () {
    $this->actingAs($this->employee)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertForbidden();

    $this->assertNotSoftDeleted('users', [
        'id' => $this->employee->id,
    ]);
});

it('visitante não consegue excluir funcionários', function () {
    $this->delete(route('admin.employees.destroy', $this->employee))
        ->assertRedirect(route('login'));

    $this->assertNotSoftDeleted('users', [
        'id' => $this->employee->id,
    ]);
});

it('funcionário excluído não pode ser excluído novamente', function () {
    $this->employee->delete();

    $this->actingAs($this->admin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertNotFound();
});

it('exclusão lógica preserva os registros de ponto do funcionário', function () {
    $timeEntry = new TimeEntry;
    $timeEntry->user_id = $this->employee->id;
    $timeEntry->type = TimeEntryType::Entry;
    $timeEntry->recorded_at = now();
    $timeEntry->save();

    $this->actingAs($this->admin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertRedirect(route('admin.employees.index'));

    $this->assertSoftDeleted('users', [
        'id' => $this->employee->id,
    ]);

    $this->assertDatabaseHas('time_entries', [
        'id' => $timeEntry->id,
        'user_id' => $this->employee->id,
    ]);
});

it('funcionário excluído não consegue fazer login', function () {
    $this->employee->password = 'SenhaSegura123!';
    $this->employee->save();

    $email = $this->employee->email;

    $this->actingAs($this->admin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertRedirect(route('admin.employees.index'));

    auth()->logout();

    $this->post(route('login'), [
        'email' => $email,
        'password' => 'SenhaSegura123!',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('exclusão lógica não altera o perfil nem o administrador responsável', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.employees.destroy', $this->employee))
        ->assertRedirect(route('admin.employees.index'));

    $employee = User::withTrashed()->findOrFail($this->employee->id);

    expect($employee->role)->toBe(UserRole::Employee)
        ->and($employee->manager_id)->toBe($this->admin->id)
        ->and($employee->deleted_at)->not->toBeNull();
});
