<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | PontoGestor</title>
</head>
<body>
    <main>
        <h1>Dashboard</h1>

        <p>
            Bem-vindo, {{ auth()->user()->name }}!
        </p>

        <p>
            Cargo: {{ auth()->user()->position ?? 'Não informado' }}
        </p>

        @if (auth()->user()->role === \App\Enums\UserRole::Admin)
            <nav aria-label="Navegação administrativa">
                <h2>Gerenciamento de funcionários</h2>

                <ul>
                    <li>
                        <a href="{{ route('admin.employees.index') }}">
                            Listar funcionários
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.employees.create') }}">
                            Cadastrar funcionário
                        </a>
                    </li>
                </ul>
            </nav>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                Sair
            </button>
        </form>
    </main>
</body>
</html>