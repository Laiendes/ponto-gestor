@extends('layouts.app')

@section('title', 'Relatório de ponto | PontoGestor')

@section('content')
    <section>
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Relatório de registros de ponto
            </h1>

            <p class="mt-2 text-slate-600">
                Consulte os registros de ponto dos seus funcionários.
            </p>
        </div>

        <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">
                Filtrar por período
            </h2>

            <form
                action="{{ route('admin.time-entries.index') }}"
                method="GET"
                class="mt-5 flex flex-wrap items-end gap-4"
            >
                <div class="w-full sm:w-auto">
                    <label
                        for="start_date"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Data inicial
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] ?? '' }}"
                        aria-invalid="{{ $errors->has('start_date') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div class="w-full sm:w-auto">
                    <label
                        for="end_date"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Data final
                    </label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] ?? '' }}"
                        aria-invalid="{{ $errors->has('end_date') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Filtrar
                </button>

                <a
                    href="{{ route('admin.time-entries.index') }}"
                    class="rounded-lg border border-slate-300 px-6 py-3 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Limpar filtros
                </a>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">
                    Registros encontrados
                </h2>
            </div>

            @if ($timeEntries->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-slate-500">
                        Nenhum registro de ponto encontrado.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                @foreach (['ID', 'Funcionário', 'Cargo', 'Idade', 'Administrador', 'Tipo', 'Data e hora'] as $heading)
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                    >
                                        {{ $heading }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($timeEntries as $timeEntry)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $timeEntry->id }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                                        {{ $timeEntry->employee_name }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $timeEntry->position ?? 'Não informado' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $timeEntry->age !== null
                                            ? $timeEntry->age . ' anos'
                                            : 'Não informada' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $timeEntry->manager_name }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($timeEntry->type === 'entry')
                                            <span class="rounded-full bg-green-100 px-3 py-1 font-medium text-green-700">
                                                Entrada
                                            </span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 font-medium text-amber-700">
                                                Saída
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ \Illuminate\Support\Carbon::parse($timeEntry->recorded_at, 'UTC')
                                            ->timezone(config('app.display_timezone'))
                                            ->format('d/m/Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $timeEntries->links() }}
                </div>
            @endif
        </div>

        <div class="mt-6">
            <a
                href="{{ route('admin.dashboard') }}"
                class="text-sm font-medium text-indigo-700 transition hover:text-indigo-900"
            >
                ← Voltar ao dashboard
            </a>
        </div>
    </section>
@endsection