<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | PontoGestor</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-12 font-sans text-slate-800">
    <main class="w-full max-w-md">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-indigo-700">
                PontoGestor
            </h1>

            <p class="mt-3 text-sm text-slate-600">
                Sistema de gerenciamento de ponto
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Entrar no sistema
                </h2>

                <p class="mt-2 text-sm text-slate-600">
                    Informe suas credenciais para acessar sua conta.
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('login') }}"
                class="space-y-6"
            >
                @csrf

                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                        autofocus
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-600" role="alert">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Senha
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('password')
                        <p class="mt-2 text-sm text-red-600" role="alert">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Entrar
                </button>
            </form>
        </div>

        <p class="mt-8 text-center text-sm text-slate-500">
            PontoGestor — Sistema de gerenciamento de ponto
        </p>
    </main>
</body>
</html>