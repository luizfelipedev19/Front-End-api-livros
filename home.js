const token = localStorage.getItem("token");

if(!token){
    window.location.href = "login.html";
}

const abrirModalLivro = document.getElementById("abrirModalLivro");
const fecharModalLivro = document.getElementById("fecharModalLivro");
const cancelarModalLivro = document.getElementById("cancelarModalLivro");
const modalLivro = document.getElementById("modalLivro");
const formLivro = document.getElementById("formLivro");

abrirModalLivro.addEventListener("click", function () {
    modalLivro.classList.remove("hidden");
});

fecharModalLivro.addEventListener("click", function () {
    modalLivro.classList.add("hidden");
});

cancelarModalLivro.addEventListener("click", function () {
    modalLivro.classList.add("hidden");
});

modalLivro.addEventListener("click", function (event) {
    if (event.target === modalLivro) {
        modalLivro.classList.add("hidden");
    }
});

formLivro.addEventListener("submit", async function (event) {
    event.preventDefault();

    const titulo = document.getElementById("titulo").value.trim();
    const autor = document.getElementById("autor").value.trim();
    const ano = Number(document.getElementById("ano").value);
    const token = localStorage.getItem("token");

    if (!token) {
        mostrarPopup("Usuário não autenticado", "error");
        return;
    }

    try {
        const resposta = await fetch("http://localhost:8080/livros", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                titulo,
                autor,
                ano
            })
        });

        const dados = await resposta.json();

        if (!resposta.ok) {
            mostrarPopup(dados.mensagem || "Erro ao cadastrar livro", "error");
            return;
        }

        mostrarPopup(dados.mensagem || "Livro cadastrado com sucesso", "success");
        formLivro.reset();
        modalLivro.classList.add("hidden");

    } catch (error) {
        console.error(error);
        mostrarPopup("Erro ao conectar com a API", "error");
    }
});


function mostrarPopup(mensagem, tipo = "success"){
    const toast = document.getElementById("toast");

    toast.textContent = mensagem;
    toast.className = `toast${tipo}`;
    toast.classList.remove("hidden");

    setTimeout(() =>{
        toast.classList.add("hidden");
    }, 3000);
}