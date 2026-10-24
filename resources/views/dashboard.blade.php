@extends('layouts.app')

@section('title', 'Dashboard | PontoGestor')

@section('content')
    <section>
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Dashboard
            </h1>

            <p class="mt-2 text-slate-600">
                Bem-vindo, {{ auth()->user()->name }}!
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Cargo: {{ auth()->user()->position ?? 'Não informado' }}
            </p>
        </div>

        @if (auth()->user()->role === \App\Enums\UserRole::Admin)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                <a
                    href="{{ route('admin.employees.index') }}"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md"
                >
                    <h2 class="text-lg font-semibold text-slate-900">
                        Funcionários
                    </h2>

                    <p class="mt-2 text-sm text-slate-600">
                        Consulte, edite e gerencie seus funcionários.
                    </p>
                </a>

                <a
                    href="{{ route('admin.employees.create') }}"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md"
                >
                    <h2 class="text-lg font-semibold text-slate-900">
                        Cadastrar funcionário
                    </h2>

                    <p class="mt-2 text-sm text-slate-600">
                        Adicione um novo funcionário à sua equipe.
                    </p>
                </a>

                <a
                    href="{{ route('admin.time-entries.index') }}"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md"
                >
                    <h2 class="text-lg font-semibold text-slate-900">
                        Relatório de ponto
                    </h2>

                    <p class="mt-2 text-sm text-slate-600">
                        Consulte os registros de entrada e saída por período.
                    </p>
                </a>

            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">

                <a
                    href="{{ route('employee.time-entries.index') }}"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md"
                >
                    <h2 class="text-lg font-semibold text-slate-900">
                        Registrar ponto
                    </h2>

                    <p class="mt-2 text-sm text-slate-600">
                        Registre sua entrada ou saída e consulte seu histórico.
                    </p>
                </a>

                <a
                    href="{{ route('employee.password.edit') }}"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md"
                >
                    <h2 class="text-lg font-semibold text-slate-900">
                        Alterar senha
                    </h2>

                    <p class="mt-2 text-sm text-slate-600">
                        Atualize sua senha de acesso com segurança.
                    </p>
                </a>

            </div>
        @endif
    </section>
@endsection