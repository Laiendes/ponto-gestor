<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Alterar senha - PontoGestor</title>
</head>
<body>
    <main>
        <h1>Alterar senha</h1>

        @if (session('success'))
            <p role="status">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employee.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password">Senha atual</label>

                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div>
                <label for="password">Nova senha</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div>
                <label for="password_confirmation">Confirmar nova senha</label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button type="submit">
                Alterar senha
            </button>
        </form>

        <p>
            <a href="{{ route('employee.dashboard') }}">
                Voltar ao dashboard
            </a>
        </p>
    </main>
</body>
</html>