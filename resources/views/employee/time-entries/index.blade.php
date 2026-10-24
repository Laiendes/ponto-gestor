<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro de ponto - PontoGestor</title>
</head>
<body>
    <main>
        <h1>Registro de ponto</h1>

        <p>
            Registre sua entrada e saída do trabalho.
        </p>

        @if (session('success'))
            <div role="status">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('employee.time-entries.store') }}" method="POST">
            @csrf

            <button type="submit">
                {{ $nextType === \App\Enums\TimeEntryType::Entry
                    ? 'Registrar entrada'
                    : 'Registrar saída' }}
            </button>
        </form>

        <h2>Histórico de registros</h2>

        @if ($timeEntries->isEmpty())
            <p>Nenhum registro de ponto encontrado.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Data</th>
                        <th scope="col">Hora</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($timeEntries as $timeEntry)
                        <tr>
                            <td>
                                {{ $timeEntry->type === \App\Enums\TimeEntryType::Entry
                                    ? 'Entrada'
                                    : 'Saída' }}
                            </td>

                            <td>
                                {{ $timeEntry->recorded_at->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $timeEntry->recorded_at->format('H:i:s') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $timeEntries->links() }}
        @endif

        <p>
            <a href="{{ route('employee.dashboard') }}">
                Voltar ao dashboard
            </a>
        </p>
    </main>
</body>
</html>