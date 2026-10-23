<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar funcionário - PontoGestor</title>
</head>
<body>
    <main>
        <h1>Editar funcionário: {{ $employee->name }}</h1>

        <p>
            Atualize os dados do funcionário. Deixe a senha em branco para mantê-la.
        </p>

        @if (session('success'))
            <div role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div role="alert">
                <strong>Não foi possível atualizar o funcionário.</strong>
                <p>Confira os campos informados e tente novamente.</p>
            </div>
        @endif

        <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <fieldset>
                <legend>Dados pessoais</legend>

                <div>
                    <label for="name">Nome completo</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $employee->name) }}"
                        maxlength="255"
                        required
                    >
                    @error('name')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cpf">CPF (somente números)</label>
                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        value="{{ old('cpf', $employee->cpf) }}"
                        maxlength="11"
                        inputmode="numeric"
                        required
                    >
                    @error('cpf')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $employee->email) }}"
                        maxlength="255"
                        required
                    >
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="birth_date">Data de nascimento</label>
                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}"
                        max="{{ now()->subDay()->toDateString() }}"
                        required
                    >
                    @error('birth_date')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="position">Cargo</label>
                    <input
                        type="text"
                        id="position"
                        name="position"
                        value="{{ old('position', $employee->position) }}"
                        maxlength="255"
                        required
                    >
                    @error('position')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <fieldset>
                <legend>Endereço</legend>

                <div>
                    <label for="zip_code">CEP (somente números)</label>
                    <input
                        type="text"
                        id="zip_code"
                        name="zip_code"
                        value="{{ old('zip_code', $employee->zip_code) }}"
                        maxlength="8"
                        inputmode="numeric"
                        required
                    >
                    @error('zip_code')
                        <p>{{ $message }}</p>
                    @enderror
                    <p id="zip_code_feedback" role="status" aria-live="polite"></p>
                </div>

                <div>
                    <label for="street">Logradouro</label>
                    <input
                        type="text"
                        id="street"
                        name="street"
                        value="{{ old('street', $employee->street) }}"
                        maxlength="255"
                        required
                    >
                    @error('street')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="number">Número</label>
                    <input
                        type="text"
                        id="number"
                        name="number"
                        value="{{ old('number', $employee->number) }}"
                        maxlength="20"
                        required
                    >
                    @error('number')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="complement">Complemento (opcional)</label>
                    <input
                        type="text"
                        id="complement"
                        name="complement"
                        value="{{ old('complement', $employee->complement) }}"
                        maxlength="255"
                    >
                    @error('complement')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="neighborhood">Bairro</label>
                    <input
                        type="text"
                        id="neighborhood"
                        name="neighborhood"
                        value="{{ old('neighborhood', $employee->neighborhood) }}"
                        maxlength="255"
                        required
                    >
                    @error('neighborhood')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city">Cidade</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city', $employee->city) }}"
                        maxlength="255"
                        required
                    >
                    @error('city')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state">Estado (UF)</label>
                    <input
                        type="text"
                        id="state"
                        name="state"
                        value="{{ old('state', $employee->state) }}"
                        maxlength="2"
                        required
                    >
                    @error('state')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <fieldset>
                <legend>Dados de acesso</legend>

                <div>
                    <label for="password">Nova senha (opcional)</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                    >
                    @error('password')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation">Confirmar nova senha</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                    >
                </div>
            </fieldset>

            <button type="submit">
                Salvar alterações
            </button>
        </form>

        <p>
            <a href="{{ route('admin.employees.index') }}">
                Voltar à listagem de funcionários
            </a>
        </p>

        <p>
            <a href="{{ route('admin.dashboard') }}">
                Voltar ao dashboard
            </a>
        </p>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const zipCodeInput = document.getElementById('zip_code');
        const feedback = document.getElementById('zip_code_feedback');

        const addressFields = {
            street: document.getElementById('street'),
            neighborhood: document.getElementById('neighborhood'),
            city: document.getElementById('city'),
            state: document.getElementById('state'),
        };

        let controller = null;

        zipCodeInput.addEventListener('input', () => {
            if (controller) {
                controller.abort();
                controller = null;
            }

            Object.values(addressFields).forEach((field) => {
                field.value = '';
            });

            feedback.textContent = '';
        });

        zipCodeInput.addEventListener('blur', async () => {
            const zipCode = zipCodeInput.value.replace(/\D/g, '');

            if (controller) {
                controller.abort();
            }

            if (zipCode.length !== 8) {
                feedback.textContent = 'Informe um CEP com 8 dígitos.';
                return;
            }

            controller = new AbortController();

            feedback.textContent = 'Consultando CEP...';

            try {
                const response = await fetch(
                    `https://viacep.com.br/ws/${zipCode}/json/`,
                    {
                        signal: controller.signal,
                    }
                );

                if (!response.ok) {
                    throw new Error('Falha na consulta do CEP.');
                }

                const address = await response.json();

                if (address.erro) {
                    feedback.textContent = 'CEP não encontrado. Preencha o endereço manualmente.';
                    return;
                }

                addressFields.street.value = address.logradouro ?? '';
                addressFields.neighborhood.value = address.bairro ?? '';
                addressFields.city.value = address.localidade ?? '';
                addressFields.state.value = address.uf ?? '';

                feedback.textContent = 'Endereço localizado com sucesso.';
            } catch (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                feedback.textContent = 'Não foi possível consultar o CEP. Preencha o endereço manualmente.';
            }
        });
    });
    </script>
</body>
</html>