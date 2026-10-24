<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relatório de ponto - PontoGestor</title>
</head>
<body>
    <main>
        <h1>Relatório de registros de ponto</h1>

        <p>Consulte os registros de ponto dos seus funcionários.</p>

        <section>
            <h2>Filtrar por período</h2>

            <form action="{{ route('admin.time-entries.index') }}" method="GET">
                <div>
                    <label for="start_date">Data inicial</label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] ?? '' }}"
                    >
                </div>

                <div>
                    <label for="end_date">Data final</label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] ?? '' }}"
                    >
                </div>

                <button type="submit">Filtrar</button>

                <a href="{{ route('admin.time-entries.index') }}">
                    Limpar filtros
                </a>
            </form>

            @if ($errors->any())
                <div role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>

        <section>
            <h2>Registros encontrados</h2>

            @if ($timeEntries->isEmpty())
                <p>Nenhum registro de ponto encontrado.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Funcionário</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Idade</th>
                            <th scope="col">Administrador</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Data e hora</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($timeEntries as $timeEntry)
                            <tr>
                                <td>{{ $timeEntry->id }}</td>

                                <td>{{ $timeEntry->employee_name }}</td>

                                <td>{{ $timeEntry->position ?? 'Não informado' }}</td>

                                <td>
                                    {{ $timeEntry->age !== null
                                        ? $timeEntry->age . ' anos'
                                        : 'Não informada' }}
                                </td>

                                <td>{{ $timeEntry->manager_name }}</td>

                                <td>
                                    {{ $timeEntry->type === 'entry'
                                        ? 'Entrada'
                                        : 'Saída' }}
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Carbon::parse($timeEntry->recorded_at)->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div>
                    {{ $timeEntries->links() }}
                </div>
            @endif
        </section>

        <p>
            <a href="{{ route('admin.dashboard') }}">
                Voltar ao dashboard
            </a>
        </p>
    </main>
</body>
</html>