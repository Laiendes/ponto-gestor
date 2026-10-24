<?php

use App\Enums\TimeEntryType;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->employee = User::factory()->employee()->create();
});

test('funcionário consegue acessar a página de registro de ponto', function () {
    $this->actingAs($this->employee)
        ->get(route('employee.time-entries.index'))
        ->assertOk()
        ->assertSee('Registro de ponto');
});

test('primeiro registro de ponto é uma entrada', function () {
    $this->actingAs($this->employee)
        ->post(route('employee.time-entries.store'))
        ->assertRedirect(route('employee.time-entries.index'))
        ->assertSessionHas('success', 'Ponto registrado com sucesso.');

    $entry = TimeEntry::query()->firstOrFail();

    expect($entry->user_id)->toBe($this->employee->id)
        ->and($entry->type)->toBe(TimeEntryType::Entry)
        ->and($entry->recorded_at)->not->toBeNull();
});

test('segundo registro de ponto é uma saída', function () {
    $this->actingAs($this->employee);

    $this->post(route('employee.time-entries.store'));
    $this->post(route('employee.time-entries.store'));

    $entries = TimeEntry::query()
        ->orderBy('id')
        ->get();

    expect($entries)->toHaveCount(2)
        ->and($entries[0]->type)->toBe(TimeEntryType::Entry)
        ->and($entries[1]->type)->toBe(TimeEntryType::Exit);
});

test('terceiro registro de ponto volta a ser uma entrada', function () {
    $this->actingAs($this->employee);

    $this->post(route('employee.time-entries.store'));
    $this->post(route('employee.time-entries.store'));
    $this->post(route('employee.time-entries.store'));

    $entries = TimeEntry::query()
        ->orderBy('id')
        ->get();

    expect($entries)->toHaveCount(3)
        ->and($entries[2]->type)->toBe(TimeEntryType::Entry);
});

test('funcionário visualiza apenas seus próprios registros', function () {
    $otherEmployee = User::factory()->employee()->create();

    $this->actingAs($otherEmployee)
        ->post(route('employee.time-entries.store'));

    $this->actingAs($this->employee)
        ->get(route('employee.time-entries.index'))
        ->assertOk()
        ->assertSee('Nenhum registro de ponto encontrado.');
});

test('administrador não consegue registrar ponto pela área do funcionário', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('employee.time-entries.store'))
        ->assertForbidden();

    $this->assertDatabaseCount('time_entries', 0);
});

test('visitante não consegue registrar ponto', function () {
    $this->post(route('employee.time-entries.store'))
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('time_entries', 0);
});

test('funcionário não consegue definir manualmente o tipo de registro', function () {
    $this->actingAs($this->employee)
        ->post(route('employee.time-entries.store'), [
            'type' => TimeEntryType::Exit->value,
        ])
        ->assertRedirect(route('employee.time-entries.index'));

    $entry = TimeEntry::query()->firstOrFail();

    expect($entry->type)->toBe(TimeEntryType::Entry);
});

test('funcionário não consegue definir manualmente a data do registro', function () {
    $this->actingAs($this->employee)
        ->post(route('employee.time-entries.store'), [
            'recorded_at' => '2020-01-01 00:00:00',
        ])
        ->assertRedirect(route('employee.time-entries.index'));

    $entry = TimeEntry::query()->firstOrFail();

    expect($entry->recorded_at->toDateString())->toBe(now()->toDateString());
});

test('página apresenta a próxima marcação correta', function () {
    $this->actingAs($this->employee)
        ->get(route('employee.time-entries.index'))
        ->assertSee('Registrar entrada');

    $this->post(route('employee.time-entries.store'));

    $this->get(route('employee.time-entries.index'))
        ->assertSee('Registrar saída');

    $this->post(route('employee.time-entries.store'));

    $this->get(route('employee.time-entries.index'))
        ->assertSee('Registrar entrada');
});

test('funcionário visualiza data e horário do ponto no fuso brasileiro', function () {
    // 25/10/2026 01:30 UTC corresponde a 24/10/2026 22:30 em São Paulo.
    $entry = new TimeEntry;

    $entry->user()->associate($this->employee);
    $entry->type = TimeEntryType::Entry;
    $entry->recorded_at = '2026-10-25 01:30:00';
    $entry->save();

    $this->actingAs($this->employee)
        ->get(route('employee.time-entries.index'))
        ->assertOk()
        ->assertSee('24/10/2026')
        ->assertSee('22:30:00')
        ->assertDontSee('25/10/2026')
        ->assertDontSee('01:30:00');
});
