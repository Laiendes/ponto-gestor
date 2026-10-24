@extends('layouts.app')

@section('title', 'Registro de ponto | PontoGestor')

@section('content')
    <section>
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Registro de ponto
            </h1>

            <p class="mt-2 text-slate-600">
                Registre sua entrada e saída do trabalho.
            </p>
        </div>

        <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">
                Nova marcação
            </h2>

            <p class="mt-2 text-sm text-slate-600">
                O sistema identifica automaticamente se a próxima marcação
                será uma entrada ou saída.
            </p>

            <form
                action="{{ route('employee.time-entries.store') }}"
                method="POST"
                class="mt-6"
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-3 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    {{ $nextType === \App\Enums\TimeEntryType::Entry
                        ? 'Registrar entrada'
                        : 'Registrar saída' }}
                </button>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">
                    Histórico de registros
                </h2>
            </div>

            @if ($timeEntries->isEmpty())
                <p class="px-6 py-8 text-sm text-slate-500">
                    Nenhum registro de ponto encontrado.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Tipo
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Data
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Hora
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($timeEntries as $timeEntry)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($timeEntry->type === \App\Enums\TimeEntryType::Entry)
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
                                        {{ $timeEntry->recorded_at->format('d/m/Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $timeEntry->recorded_at->format('H:i:s') }}
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
                href="{{ route('employee.dashboard') }}"
                class="text-sm font-medium text-indigo-700 hover:text-indigo-900"
            >
                ← Voltar ao dashboard
            </a>
        </div>
    </section>
@endsection