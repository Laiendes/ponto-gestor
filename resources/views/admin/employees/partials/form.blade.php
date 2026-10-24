@php
    $employee = $employee ?? null;
    $editing = $employee !== null;

    $fields = [
        ['name', 'Nome completo', 'text', 255, true],
        ['cpf', 'CPF (somente números)', 'text', 11, true],
        ['email', 'E-mail', 'email', 255, true],
        ['birth_date', 'Data de nascimento', 'date', null, true],
        ['position', 'Cargo', 'text', 255, true],
    ];

    $addressFields = [
        ['zip_code', 'CEP (somente números)', 8, true],
        ['street', 'Logradouro', 255, true],
        ['number', 'Número', 20, true],
        ['complement', 'Complemento (opcional)', 255, false],
        ['neighborhood', 'Bairro', 255, true],
        ['city', 'Cidade', 255, true],
        ['state', 'Estado (UF)', 2, true],
    ];

    $inputClass = 'w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200';
@endphp

<fieldset class="space-y-5">
    <legend class="mb-5 text-lg font-semibold text-slate-900">
        Dados pessoais
    </legend>

    <div class="grid gap-5 md:grid-cols-2">
        @foreach ($fields as [$name, $label, $type, $maxlength, $required])
            <div>
                <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-slate-700">
                    {{ $label }}
                </label>

                <input
                    type="{{ $type }}"
                    id="{{ $name }}"
                    name="{{ $name }}"
                    value="{{ old($name, $employee?->{$name} instanceof \Carbon\CarbonInterface ? $employee->{$name}->format('Y-m-d') : $employee?->{$name}) }}"
                    @if ($maxlength) maxlength="{{ $maxlength }}" @endif
                    @if ($name === 'cpf') inputmode="numeric" @endif
                    @if ($name === 'birth_date') max="{{ now()->subDay()->toDateString() }}" @endif
                    @if ($required) required @endif
                    aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
                    class="{{ $inputClass }}"
                >

                @error($name)
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
</fieldset>

<fieldset class="space-y-5 border-t border-slate-200 pt-8">
    <legend class="mb-5 text-lg font-semibold text-slate-900">
        Endereço
    </legend>

    <div class="grid gap-5 md:grid-cols-2">
        @foreach ($addressFields as [$name, $label, $maxlength, $required])
            <div>
                <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-slate-700">
                    {{ $label }}
                </label>

                <input
                    type="text"
                    id="{{ $name }}"
                    name="{{ $name }}"
                    value="{{ old($name, $employee?->{$name}) }}"
                    maxlength="{{ $maxlength }}"
                    @if (in_array($name, ['zip_code'])) inputmode="numeric" @endif
                    @if ($required) required @endif
                    aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
                    class="{{ $inputClass }}"
                >

                @error($name)
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($name === 'zip_code')
                    <p
                        id="zip_code_feedback"
                        role="status"
                        aria-live="polite"
                        class="mt-2 text-sm text-slate-500"
                    ></p>
                @endif
            </div>
        @endforeach
    </div>
</fieldset>

<fieldset class="space-y-5 border-t border-slate-200 pt-8">
    <legend class="mb-5 text-lg font-semibold text-slate-900">
        Dados de acesso
    </legend>

    @if ($editing)
        <p class="text-sm text-slate-500">
            Deixe os campos de senha em branco para manter a senha atual.
        </p>
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                {{ $editing ? 'Nova senha (opcional)' : 'Senha' }}
            </label>

            <input
                type="password"
                id="password"
                name="password"
                autocomplete="new-password"
                @unless ($editing) required @endunless
                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                class="{{ $inputClass }}"
            >

            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">
                {{ $editing ? 'Confirmar nova senha' : 'Confirmar senha' }}
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                autocomplete="new-password"
                @unless ($editing) required @endunless
                class="{{ $inputClass }}"
            >
        </div>
    </div>
</fieldset>