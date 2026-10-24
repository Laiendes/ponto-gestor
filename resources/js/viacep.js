document.addEventListener('DOMContentLoaded', () => {
    const zipCodeInput = document.getElementById('zip_code');
    const feedback = document.getElementById('zip_code_feedback');

    if (!zipCodeInput || !feedback) {
        return;
    }

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
            if (field) {
                field.value = '';
            }
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
                feedback.textContent =
                    'CEP não encontrado. Preencha o endereço manualmente.';

                return;
            }

            if (addressFields.street) {
                addressFields.street.value = address.logradouro ?? '';
            }

            if (addressFields.neighborhood) {
                addressFields.neighborhood.value = address.bairro ?? '';
            }

            if (addressFields.city) {
                addressFields.city.value = address.localidade ?? '';
            }

            if (addressFields.state) {
                addressFields.state.value = address.uf ?? '';
            }

            feedback.textContent = 'Endereço localizado com sucesso.';
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            feedback.textContent =
                'Não foi possível consultar o CEP. Preencha o endereço manualmente.';
        }
    });
});