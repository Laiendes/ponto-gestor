<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | PontoGestor</title>
</head>
<body>
    <main>
        <h1>PontoGestor</h1>

        <h2>Entrar no sistema</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email">E-mail</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >

                @error('email')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password">Senha</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >

                @error('password')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">
                Entrar
            </button>
        </form>
    </main>
</body>
</html>