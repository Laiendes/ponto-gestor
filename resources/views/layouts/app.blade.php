<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PontoGestor')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800">

    <header class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4">

            <a
                href="{{ route('dashboard') }}"
                class="text-xl font-bold tracking-tight text-indigo-700"
            >
                PontoGestor
            </a>

            <nav aria-label="Navegação principal" class="flex flex-wrap items-center gap-4 text-sm font-medium">

                @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    <a
                        href="{{ route('admin.employees.index') }}"
                        class="text-slate-600 transition hover:text-indigo-700"
                    >
                        Funcionários
                    </a>

                    <a
                        href="{{ route('admin.time-entries.index') }}"
                        class="text-slate-600 transition hover:text-indigo-700"
                    >
                        Relatório de ponto
                    </a>
                @else
                    <a
                        href="{{ route('employee.time-entries.index') }}"
                        class="text-slate-600 transition hover:text-indigo-700"
                    >
                        Registrar ponto
                    </a>

                    <a
                        href="{{ route('employee.password.edit') }}"
                        class="text-slate-600 transition hover:text-indigo-700"
                    >
                        Alterar senha
                    </a>
                @endif

            </nav>

            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-600">
                    {{ auth()->user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium transition hover:bg-slate-100"
                    >
                        Sair
                    </button>
                </form>
            </div>

        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">

        @if (session('success'))
            <div
                role="status"
                class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
            >
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                role="alert"
                class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
            >
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    <footer class="mt-12 border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-5 text-center text-sm text-slate-500">
            PontoGestor — Sistema de gerenciamento de ponto
        </div>
    </footer>

</body>
</html>