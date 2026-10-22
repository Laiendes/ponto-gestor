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

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                Sair
            </button>
        </form>
    </main>
</body>
</html>