const stars = document.querySelectorAll('.star');
const errorMessage = document.getElementById('error-message');
let userRating = 0;

// Zobrazovani hvezdicek k hodnoceni
stars.forEach(star => {
    star.addEventListener('mouseover', () => {
        const value = star.getAttribute('data-value');
        highlightStars(value);
    });

    star.addEventListener('click', () => {
        if (userEmail) {
            userRating = star.getAttribute('data-value');
            updateSelectedRating();
        } else {
            errorMessage.textContent = 'Pro hodnocení musíte být přihlášen!';
            errorMessage.classList.remove("d-none");
            errorMessage.classList.add("d-block");
        }

    });

    star.addEventListener('mouseleave', () => {
        resetStars();
    });
});

function highlightStars(value) {
    stars.forEach(star => {
        const starValue = star.getAttribute('data-value');
        if (starValue <= value) {
            star.style.backgroundImage = `url('icon/star-full.png')`;
        } else {
            star.style.backgroundImage = `url('icon/star-empty.png')`;
        }
    });
}

function resetStars() {
    highlightStars(0);
}

// Po kliknuti se hodnoceni odesle a zobrazi se novy yeti
async function updateSelectedRating() {

    var yetiID = yetis[i].yetiID;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/rating_controller', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };
    var data = new FormData();
    data.append('userRating', userRating);
    data.append('userEmail', userEmail);
    data.append('yetiID', yetiID);
    xhr.send(data);
    updateDisplay();
}

resetStars();