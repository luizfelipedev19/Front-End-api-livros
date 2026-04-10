document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formRegister');

    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const nomeInput = document.getElementById('nome');
        const emailInput = document.getElementById('email');
        const senhaInput = document.getElementById('senha');

        const nome = nomeInput?.value.trim() || '';
        const email = emailInput?.value.trim() || '';
        const senha = senhaInput?.value || '';

        if (!nome || !email || !senha) {
            mostrarPopup('Preencha todos os campos', 'erro');
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');

        try {
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Cadastrando...';
            }

            const formData = new FormData();
            formData.append('nome', nome);
            formData.append('email', email);
            formData.append('senha', senha);

            const response = await fetch('/Front-Biblioteca/register', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            let data = {};

            try {
                data = await response.json();
            } catch (jsonError) {
                throw new Error('Resposta inválida do servidor.');
            }

            if (data.success) {
                mostrarPopup(data.mensagem || 'Usuário cadastrado com sucesso', 'success');

                setTimeout(() => {
                    window.location.href = data.redirect || '/Front-Biblioteca/';
                }, 1200);

                return;
            }

            mostrarPopup(data.mensagem || 'Erro ao cadastrar usuário', 'erro');

        } catch (error) {
            console.error('Erro no cadastro:', error);
            mostrarPopup('Erro ao enviar cadastro. Tente novamente.', 'erro');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Cadastrar';
            }
        }
    });
});