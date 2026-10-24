@extends('layouts.app')

@section('title', 'Cadastrar funcionário | PontoGestor')

@section('content')
    <section class="mx-auto max-w-5xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Cadastrar funcionário
            </h1>

            <p class="mt-2 text-slate-600">
                Preencha os dados abaixo para cadastrar um novo funcionário.
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form
                method="POST"
                action="{{ route('admin.employees.store') }}"
                class="space-y-8"
            >
                @csrf

                @include('admin.employees.partials.form')

                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-200 pt-6">
                    <a
                        href="{{ route('admin.employees.index') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-700"
                    >
                        ← Ver funcionários cadastrados
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Cadastrar funcionário
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <a
                href="{{ route('admin.dashboard') }}"
                class="text-sm font-medium text-indigo-700 hover:text-indigo-900"
            >
                ← Voltar ao dashboard
            </a>
        </div>
    </section>
@endsection