<?php

use App\Enums\TimeEntryType;
use App\Enums\UserRole;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cria um administrador no banco de dados', function () {
    $admin = User::factory()->admin()->create();

    expect($admin->exists)->toBeTrue()
        ->and($admin->role)->toBe(UserRole::Admin)
        ->and($admin->manager_id)->toBeNull();

    $this->assertDatabaseHas('users', [
        'id' => $admin->id,
        'cpf' => $admin->cpf,
        'role' => UserRole::Admin->value,
    ]);
});

test('cria um funcionário vinculado ao administrador', function () {
    $admin = User::factory()->admin()->create();

    $employee = User::factory()->employee()->create([
        'manager_id' => $admin->id,
    ]);

    expect($employee->exists)->toBeTrue()
        ->and($employee->role)->toBe(UserRole::Employee)
        ->and($employee->manager_id)->toBe($admin->id)
        ->and($employee->manager->is($admin))->toBeTrue();

    $this->assertDatabaseHas('users', [
        'id' => $employee->id,
        'manager_id' => $admin->id,
        'role' => UserRole::Employee->value,
    ]);
});

test('lista apenas os funcionários vinculados ao administrador', function () {
    $admin = User::factory()->admin()->create();
    $otherAdmin = User::factory()->admin()->create();

    $employee = User::factory()->employee()->create([
        'manager_id' => $admin->id,
    ]);

    $otherEmployee = User::factory()->employee()->create([
        'manager_id' => $otherAdmin->id,
    ]);

    $employees = $admin->employees;

    expect($employees)->toHaveCount(1)
        ->and($employees->first()->is($employee))->toBeTrue()
        ->and($employees->contains($otherEmployee))->toBeFalse();
});

test('relaciona os registros de ponto ao funcionário', function () {
    $admin = User::factory()->admin()->create();

    $employee = User::factory()->employee()->create([
        'manager_id' => $admin->id,
    ]);

    $entry = new TimeEntry;
    $entry->user_id = $employee->id;
    $entry->type = TimeEntryType::Entry;
    $entry->recorded_at = now();
    $entry->save();

    $exit = new TimeEntry;
    $exit->user_id = $employee->id;
    $exit->type = TimeEntryType::Exit;
    $exit->recorded_at = now()->addHours(8);
    $exit->save();

    expect($employee->timeEntries)->toHaveCount(2)
        ->and($entry->user->is($employee))->toBeTrue()
        ->and($exit->user->is($employee))->toBeTrue()
        ->and($entry->type)->toBe(TimeEntryType::Entry)
        ->and($exit->type)->toBe(TimeEntryType::Exit);

    $this->assertDatabaseCount('time_entries', 2);
});

test('exclui o funcionário logicamente e preserva seus registros de ponto', function () {
    $admin = User::factory()->admin()->create();

    $employee = User::factory()->employee()->create([
        'manager_id' => $admin->id,
    ]);

    $timeEntry = new TimeEntry;
    $timeEntry->user_id = $employee->id;
    $timeEntry->type = TimeEntryType::Entry;
    $timeEntry->recorded_at = now();
    $timeEntry->save();

    $employee->delete();

    expect(User::find($employee->id))->toBeNull()
        ->and(User::withTrashed()->find($employee->id))->not->toBeNull();

    $this->assertSoftDeleted('users', [
        'id' => $employee->id,
    ]);

    $this->assertDatabaseHas('time_entries', [
        'id' => $timeEntry->id,
        'user_id' => $employee->id,
    ]);
});

test('impede registros de ponto vinculados a usuários inexistentes', function () {
    $timeEntry = new TimeEntry;
    $timeEntry->user_id = 999999999;
    $timeEntry->type = TimeEntryType::Entry;
    $timeEntry->recorded_at = now();

    expect(fn () => $timeEntry->save())
        ->toThrow(QueryException::class);

    $this->assertDatabaseCount('time_entries', 0);
});

test('impede a exclusão física de funcionário com registros de ponto', function () {
    $admin = User::factory()->admin()->create();

    $employee = User::factory()->employee()->create([
        'manager_id' => $admin->id,
    ]);

    $timeEntry = new TimeEntry;
    $timeEntry->user_id = $employee->id;
    $timeEntry->type = TimeEntryType::Entry;
    $timeEntry->recorded_at = now();
    $timeEntry->save();

    expect(fn () => $employee->forceDelete())
        ->toThrow(QueryException::class);

    $this->assertDatabaseHas('users', [
        'id' => $employee->id,
    ]);

    $this->assertDatabaseHas('time_entries', [
        'id' => $timeEntry->id,
        'user_id' => $employee->id,
    ]);
});
