// Vzhled tlacitka po prihlaseni/pred prihlasenim
var button = document.getElementById('loginButton');
if (userRole === 'ROLE_USER') {
    button.innerText = "Odhlásit";
    button.classList.remove("btn-outline-primary");
    button.classList.add("btn-outline-warning");
}