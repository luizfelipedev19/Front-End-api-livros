const formRegister = document.getElementById("formRegister");


formRegister.addEventListener("submit", async function (event) {
    event.preventDefault();

    const nome = document.getElementById("nome").value.trim();
    const email = document.getElementById("email").value.trim();
    const senha = document.getElementById("senha").value.trim();
    const botao = formRegister.querySelector("button[type='submit']");

    botao.disable = true;
    botao.textContent = "Cadastrando...";


    try{
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
        const dados = await resposta.json();
        console.log("Resposta da api", dados);

        if(!resposta.ok){
            mostrarPopup(dados.mensagem || "Erro ao se cadastrar", "error" );
            return;
        }

        mostrarPopup(dados.mensagem || "Cadastro realizado com sucesso");
        formRegister.reset();

        setTimeout(() => {
            window.location.href = "index.html";
        }, 2000)
        
    } catch(error){
        mostrarPopup(dados.mensagem || "Erro ao conectar com a api");
        console.error(error);
        
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