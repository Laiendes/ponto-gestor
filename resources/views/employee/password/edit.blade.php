@extends('layouts.app')

@section('title', 'Alterar senha | PontoGestor')

@section('content')
    <section class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Alterar senha
            </h1>

            <p class="mt-2 text-slate-600">
                Atualize sua senha de acesso para manter sua conta protegida.
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form
                action="{{ route('employee.password.update') }}"
                method="POST"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                <div>
                    <label
                        for="current_password"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Senha atual
                    </label>

                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        required
                        aria-invalid="{{ $errors->has('current_password') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Nova senha
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        required
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Confirmar nova senha
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                        aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-200 pt-6">
                    <a
                        href="{{ route('employee.dashboard') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-700"
                    >
                        ← Voltar ao dashboard
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Alterar senha
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection