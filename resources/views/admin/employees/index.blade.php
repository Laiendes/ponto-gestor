@extends('layouts.app')

@section('title', 'Funcionários | PontoGestor')

@section('content')
    <section>
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Funcionários
                </h1>

                <p class="mt-2 text-slate-600">
                    Gerencie os funcionários vinculados à sua conta.
                </p>
            </div>

            <a
                href="{{ route('admin.employees.create') }}"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                + Cadastrar funcionário
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">
                    Funcionários cadastrados
                </h2>
            </div>

            @if ($employees->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-slate-500">
                        Nenhum funcionário cadastrado.
                    </p>

                    <a
                        href="{{ route('admin.employees.create') }}"
                        class="mt-4 inline-block text-sm font-medium text-indigo-700 hover:text-indigo-900"
                    >
                        Cadastrar primeiro funcionário
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Nome
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    CPF
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    E-mail
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Cargo
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Data de cadastro
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Ações
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($employees as $employee)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                                        {{ $employee->name }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $employee->cpf }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $employee->email }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $employee->position ?? 'Não informado' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $employee->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a
                                                href="{{ route('admin.employees.edit', $employee) }}"
                                                class="text-sm font-medium text-indigo-700 transition hover:text-indigo-900"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="{{ route('admin.employees.destroy', $employee) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza de que deseja excluir este funcionário?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-sm font-medium text-red-600 transition hover:text-red-800"
                                                    aria-label="Excluir funcionário {{ $employee->name }}"
                                                >
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $employees->links() }}
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