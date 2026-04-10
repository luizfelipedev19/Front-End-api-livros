document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formLogin');

    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const emailInput = document.getElementById('email');
        const senhaInput = document.getElementById('senha');

        const email = emailInput?.value.trim() || '';
        const senha = senhaInput?.value || '';

        if (!email || !senha) {
            mostrarPopup('Preencha todos os campos', 'erro');
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');

        try {
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Entrando...';
            }

            const formData = new FormData();
            formData.append('email', email);
            formData.append('senha', senha);

            const response = await fetch('/Front-Biblioteca/login', {
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
            } catch (error) {
                throw new Error('Resposta inválida do servidor');
            }

            if (data.success) {
                mostrarPopup(data.mensagem || 'Login realizado com sucesso!', 'success');

                setTimeout(() => {
                    window.location.href = data.redirect || '/Front-Biblioteca/home';
                }, 1000);

                return;
            }

            mostrarPopup(data.mensagem || 'Email ou senha inválidos', 'erro');

        } catch (error) {
            console.error('Erro no login:', error);
            mostrarPopup('Erro ao enviar login. Tente novamente.', 'erro');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Login';
            }
        }
    });
});