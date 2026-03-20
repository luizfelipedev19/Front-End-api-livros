const formRegister = document.getElementById("formRegister");

formRegister.addEventListener("submit", async function (event) {
    event.preventDefault();

    const nome = document.getElementById("nome").value.trim();
    const email = document.getElementById("email").value.trim();
    const senha = document.getElementById("senha").value.trim();
    const botao = formRegister.querySelector("button[type='submit']");

    botao.disabled = true;
    botao.textContent = "Cadastrando...";

    try {
        const resposta = await fetch("http://localhost:8080/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                nome: nome,
                email: email,
                senha: senha
            })
        });

        const texto = await resposta.text();
        console.log("Resposta bruta da API:", texto);

        let dados;

        try {
            dados = JSON.parse(texto);
        } catch {
            throw new Error("A API não retornou JSON válido.");
        }

        console.log("Resposta da API:", dados);

        if (!resposta.ok) {
            mostrarPopup(dados.mensagem || "Erro ao se cadastrar", "error");
            return;
        }

        mostrarPopup(dados.mensagem || "Cadastro realizado com sucesso", "success");
        formRegister.reset();

        setTimeout(() => {
            window.location.href = "index.html";
        }, 2000);

    } catch (error) {
        console.error("Erro ao conectar com a API:", error);
        mostrarPopup(error.message || "Erro ao conectar com a API", "error");
    } finally {
        botao.disabled = false;
        botao.textContent = "Cadastrar";
    }
});

function mostrarPopup(mensagem, tipo = "success") {
    const toast = document.getElementById("toast");

    toast.textContent = mensagem;
    toast.className = `toast ${tipo}`;
    toast.classList.remove("hidden");

    setTimeout(() => {
        toast.classList.add("hidden");
    }, 3000);
}