<?php

use App\Enums\TimeEntryType;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();

    $this->employee = User::factory()->employee()->create([
        'manager_id' => $this->admin->id,
        'name' => 'Maria Oliveira',
        'position' => 'Analista',
        'birth_date' => '2000-01-15',
    ]);
});

function createTimeEntry(User $employee, string $date, TimeEntryType $type = TimeEntryType::Entry): TimeEntry
{
    $entry = new TimeEntry;

    $entry->user()->associate($employee);
    $entry->type = $type;
    $entry->recorded_at = $date;
    $entry->save();

    return $entry;
}

test('administrador consegue acessar o relatório de ponto', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index'))
        ->assertOk()
        ->assertSee('Relatório de registros de ponto');
});

test('relatório apresenta os dados do funcionário e do administrador', function () {
    $entry = createTimeEntry(
        $this->employee,
        '2026-10-20 08:30:45'
    );

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index'))
        ->assertOk()
        ->assertSee('Maria Oliveira')
        ->assertSee('Analista')
        ->assertSee($this->admin->name)
        ->assertSee('20/10/2026 08:30:45')
        ->assertSee((string) $entry->id);
});

test('administrador visualiza apenas registros de seus funcionários', function () {
    $otherAdmin = User::factory()->admin()->create();

    $otherEmployee = User::factory()->employee()->create([
        'manager_id' => $otherAdmin->id,
        'name' => 'Funcionário de Outro Administrador',
    ]);

    createTimeEntry($this->employee, '2026-10-20 08:00:00');
    createTimeEntry($otherEmployee, '2026-10-20 09:00:00');

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index'))
        ->assertOk()
        ->assertSee('Maria Oliveira')
        ->assertDontSee('Funcionário de Outro Administrador');
});

test('relatório filtra registros pela data inicial', function () {
    createTimeEntry($this->employee, '2026-10-10 08:00:00');
    createTimeEntry($this->employee, '2026-10-20 09:00:00');

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index', [
            'start_date' => '2026-10-15',
        ]))
        ->assertOk()
        ->assertSee('20/10/2026 09:00:00')
        ->assertDontSee('10/10/2026 08:00:00');
});

test('relatório filtra registros pela data final', function () {
    createTimeEntry($this->employee, '2026-10-10 08:00:00');
    createTimeEntry($this->employee, '2026-10-20 09:00:00');

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index', [
            'end_date' => '2026-10-15',
        ]))
        ->assertOk()
        ->assertSee('10/10/2026 08:00:00')
        ->assertDontSee('20/10/2026 09:00:00');
});

test('relatório inclui registros até o último segundo da data final', function () {
    createTimeEntry($this->employee, '2026-10-20 23:59:59');

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index', [
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-20',
        ]))
        ->assertOk()
        ->assertSee('20/10/2026 23:59:59');
});

test('relatório rejeita período com data final anterior à inicial', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index', [
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-10',
        ]))
        ->assertSessionHasErrors('end_date');
});

test('relatório rejeita datas em formato inválido', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index', [
            'start_date' => '20/10/2026',
        ]))
        ->assertSessionHasErrors('start_date');
});

test('registros de funcionários excluídos permanecem no relatório', function () {
    createTimeEntry($this->employee, '2026-10-20 08:00:00');

    $this->employee->delete();

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index'))
        ->assertOk()
        ->assertSee('Maria Oliveira');
});

test('funcionário não consegue acessar o relatório administrativo', function () {
    $this->actingAs($this->employee)
        ->get(route('admin.time-entries.index'))
        ->assertForbidden();
});

test('visitante não consegue acessar o relatório administrativo', function () {
    $this->get(route('admin.time-entries.index'))
        ->assertRedirect(route('login'));
});

test('relatório apresenta dez registros por página', function () {
    for ($i = 0; $i < 12; $i++) {
        createTimeEntry(
            $this->employee,
            sprintf('2026-10-%02d 08:00:00', $i + 1)
        );
    }

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index'))
        ->assertOk()
        ->assertViewHas('timeEntries', function ($entries) {
            return $entries->count() === 10
                && $entries->total() === 12;
        });
});

test('segunda página apresenta os registros restantes', function () {
    for ($i = 0; $i < 12; $i++) {
        createTimeEntry(
            $this->employee,
            sprintf('2026-10-%02d 08:00:00', $i + 1)
        );
    }

    $this->actingAs($this->admin)
        ->get(route('admin.time-entries.index', [
            'page' => 2,
        ]))
        ->assertOk()
        ->assertViewHas('timeEntries', function ($entries) {
            return $entries->count() === 2
                && $entries->currentPage() === 2;
        });
});
