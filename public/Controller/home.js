function getAccessToken() {
    return localStorage.getItem("access_token");
}

function getRefreshToken() {
    return localStorage.getItem("refresh_token");
}

if (!getAccessToken()) {
    window.location.href = "login.html";
}

const abrirModalLivro = document.getElementById("abrirModalLivro");
const fecharModalLivro = document.getElementById("fecharModalLivro");
const cancelarModalLivro = document.getElementById("cancelarModalLivro");
const modalLivro = document.getElementById("modalLivro");
const formLivro = document.getElementById("formLivro");

function abrirModal() {
    modalLivro.classList.remove("hidden");
}

function fecharModal() {
    modalLivro.classList.add("hidden");
}

if (abrirModalLivro) {
    abrirModalLivro.addEventListener("click", abrirModal);
}

if (fecharModalLivro) {
    fecharModalLivro.addEventListener("click", fecharModal);
}

if (cancelarModalLivro) {
    cancelarModalLivro.addEventListener("click", fecharModal);
}

if (modalLivro) {
    modalLivro.addEventListener("click", function (event) {
        if (event.target === modalLivro) {
            fecharModal();
        }
    });
}

if (formLivro) {
    formLivro.addEventListener("submit", async function (event) {
        event.preventDefault();

        const titulo = document.getElementById("titulo").value.trim();
        const autor = document.getElementById("autor").value.trim();
        const ano = Number(document.getElementById("ano").value);

        let token = getAccessToken();

        if (!token) {
            mostrarPopup("Usuário não autenticado", "error");
            window.location.href = "login.html";
            return;
        }

        try {
            let resposta = await fetch("http://localhost:8080/livros", {
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

            if (resposta.status === 401) {
                token = await renovarAccessToken();

                if (!token) {
                    mostrarPopup("Sessão expirada. Faça login novamente.", "error");
                    window.location.href = "login.html";
                    return;
                }

                resposta = await fetch("http://localhost:8080/livros", {
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
            }

            const texto = await resposta.text();
            console.log("Resposta da API:", texto);

            let dados;

            try {
                dados = JSON.parse(texto);
            } catch {
                throw new Error("A API não retornou JSON válido");
            }

            if (!resposta.ok) {
                mostrarPopup(dados.mensagem || "Erro ao cadastrar livro", "error");
                return;
            }

            mostrarPopup(dados.mensagem || "Livro cadastrado com sucesso", "success");
            formLivro.reset();
            fecharModal();

        } catch (error) {
            console.error(error);
            mostrarPopup(error.message || "Erro ao conectar com a API", "error");
        }
    });
}


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

async function enviarLivro(dados) {
if (formLivro) {
    formLivro.addEventListener("submit", async function (event) {
        event.preventDefault();

        const titulo = document.getElementById("titulo").value.trim();
        const autor = document.getElementById("autor").value.trim();
        const ano = Number(document.getElementById("ano").value);

    
        try {
            let resposta = await fetch("", {
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

            if (resposta.status === 401) {
                token = await renovarAccessToken();

                if (!token) {
                    mostrarPopup("Sessão expirada. Faça login novamente.", "error");
                    window.location.href = "login.html";
                    return;
                }

                resposta = await fetch("http://localhost:8080/livros", {
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
            }

            const texto = await resposta.text();
            console.log("Resposta da API:", texto);

            let dados;

            try {
                dados = JSON.parse(texto);
            } catch {
                throw new Error("A API não retornou JSON válido");
            }

            if (!resposta.ok) {
                mostrarPopup(dados.mensagem || "Erro ao cadastrar livro", "error");
                return;
            }

            mostrarPopup(dados.mensagem || "Livro cadastrado com sucesso", "success");
            formLivro.reset();
            fecharModal();

        } catch (error) {
            console.error(error);
            mostrarPopup(error.message || "Erro ao conectar com a API", "error");
        }
    });
}
}

export {
    enviarLivro
}

//Controler só vai enviar os fetchs para o controllador php (app)