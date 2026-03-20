const formLogin = document.getElementById("formLogin");

formLogin.addEventListener("submit", async function (event) {
    event.preventDefault();

    const email = document.getElementById("email").value.trim();
    const senha = document.getElementById("senha").value.trim();

    try{
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

        console.log("status", resposta.status);
        console.log("ok", resposta.ok);
        console.log("dados", dados);

        if (!resposta.ok || !dados.token){

            localStorage.removeItem("token");
            mostrarPopup(dados.mensagem || "Erro ao fazer login", "error" );
            return;
        }

        localStorage.setItem("token", dados.token);
        mostrarPopup(dados.mensagem || "Login realizado com sucesso", "success");

        setTimeout(() => { 
            window.location.href = "home.html";
        }, 2000)
        
    } catch (error){
        mensagemLogin.textContent = "Erro ao conectar com a API";
        console.error(error);
    }
});

function mostrarPopup(mensagem, tipo = "success"){
    const toast = document.getElementById("toast");

    toast.textContent = mensagem;
    toast.className = `toast ${tipo}`;
    toast.classList.remove("hidden");

    setTimeout(() =>{
        toast.classList.add("hidden");
    }, 3000);
}