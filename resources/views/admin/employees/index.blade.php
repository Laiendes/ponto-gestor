<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Funcionários - PontoGestor</title>
</head>
<body>
    <main>
        <h1>Funcionários</h1>

        <p>
            Gerencie os funcionários vinculados à sua conta.
        </p>

        <a href="{{ route('admin.employees.create') }}">
            Cadastrar funcionário
        </a>

        @if ($employees->isEmpty())
            <p>Nenhum funcionário cadastrado.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Data de cadastro</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->cpf }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $employees->links() }}
        @endif

        <p>
            <a href="{{ route('admin.dashboard') }}">
                Voltar ao dashboard
            </a>
        </p>
    </main>
</body>
</html>