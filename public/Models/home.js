
const logo = document.querySelector(".logo");
const menu = document.querySelector(".menu");
const menuOverlay = document.getElementById("menuOverlay");

if (logo && menu) {
    logo.addEventListener("click", () => {
        menu.classList.toggle("aberto");
        if (menuOverlay) menuOverlay.classList.toggle("aberto");
    });

    if(menuOverlay) {
        menuOverlay.addEventListener("click", () => {
            menu.classList.remove("aberto");
            menuOverlay.classList.remove("aberto");
        });
    }
}