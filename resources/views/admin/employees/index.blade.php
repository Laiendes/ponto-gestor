```blade
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

        @if (session('success'))
            <div role="status">
                {{ session('success') }}
            </div>
        @endif

        <p>
            <a href="{{ route('admin.employees.create') }}">
                Cadastrar funcionário
            </a>
        </p>

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
                        <th scope="col">Ações</th>
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

                            <td>
                                <a href="{{ route('admin.employees.edit', $employee) }}">
                                    Editar
                                </a>

                                <form
                                    action="{{ route('admin.employees.destroy', $employee) }}"
                                    method="POST"
                                    onsubmit="return confirm('Tem certeza de que deseja excluir este funcionário?');"
                                    style="display: inline;"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">
                                        Excluir
                                    </button>
                                </form>
                            </td>
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