function mostrarPopup(mensagem, tipo = "erro") {
    const popup = document.createElement("toast");

    popup.className = `toast ${tipo}`;
    popup.innerText = mensagem;

    document.body.appendChild(popup);

    setTimeout(() => {
        popup.remove();
    }, 3000);
}