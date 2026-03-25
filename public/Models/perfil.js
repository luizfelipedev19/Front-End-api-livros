const btnAlterarFoto = document.getElementById("btnAlterarFoto");

if(btnAlterarFoto){
    btnAlterarFoto.addEventListener("click", () => {
        abrirModalGenerico(modalFoto);
    });
}