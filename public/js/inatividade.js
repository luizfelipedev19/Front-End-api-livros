const TEMPO_LIMITE = 15 * 60 * 1000; // 15 minutos em milissegundos
let ultimoEvento= Date.now();
let sessaoExpirada = false;

function registrarInatividade() {
    if(sessaoExpirada){
        window.location.href = "/Front-Biblioteca/?error=session_expired";
        return;
    }

    ultimoEvento = Date.now();
}

function verificarInatividade() {
    const agora = Date.now();
    const tempoParado = agora - ultimoEvento;

    if(tempoParado > TEMPO_LIMITE){
        sessaoExpirada = true;
    }
}

document.addEventListener("mousemove", registrarInatividade);
document.addEventListener("keydown", registrarInatividade);
document.addEventListener("click", registrarInatividade);
document.addEventListener("scroll", registrarInatividade);  
setInterval(verificarInatividade, 1000); // Verificar a cada segundo