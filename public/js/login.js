const formLogin = document.getElementById("formLogin");

formLogin.addEventListener("submit", async function (event) {
    event.preventDefault();

    const email = document.getElementById("email").value.trim();
    const senha = document.getElementById("senha").value.trim();

    try {
        const resposta = await fetch("http://localhost:8080/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email: email,
                senha: senha
            })
        });

        const dados = await resposta.json();


        if (!resposta.ok || !dados.access_token || !dados.refresh_token) {
            localStorage.removeItem("access_token");
            localStorage.removeItem("refresh_token");
            mostrarPopup(dados.mensagem || "Erro ao fazer login", "error");
            return;
        }

        localStorage.setItem("access_token", dados.access_token);
        localStorage.setItem("refresh_token", dados.refresh_token);

        mostrarPopup(dados.mensagem || "Login realizado com sucesso", "success");

        setTimeout(() => {
            window.location.href = "home.html";
        }, 2000);

    } catch (error) {
        console.error(error);
        mostrarPopup("Erro ao conectar com a API", "error");
    }
});

function mostrarPopup(mensagem, tipo = "success") {
    const toast = document.getElementById("toast");

    if (!toast) {
        console.error("Elemento #toast não encontrado");
        return;
    }

    toast.textContent = mensagem;
    toast.className = `toast ${tipo}`;
    toast.classList.remove("hidden");

    setTimeout(() => {
        toast.classList.add("hidden");
    }, 3000);
}


//Essa pasta js eu vou colocar todos os recursos de javascript que vão ser utilizados para todos ou para vários