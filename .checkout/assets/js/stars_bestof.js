let rating = [];
let ratingRounded = [];
for (let k = 0; k < ratings.length; k++) {
    rating[k] = ratings[k].rating;
    ratingRounded[k] = round(rating[k]);
}

highlightStars(rating, ratingRounded);

// Zobrazovani hodnoceni 'hvezdicek', zobrazuje s presnosti na ctvrtinu hvezdicky
function highlightStars(rating, ratingRounded) {
    for (let l = 1; l <= ratingRounded.length; l++) {
        let parent = document.getElementById(l);
        parent.title = rating[l - 1];
        let child = parent.getElementsByTagName('span');
        for (let i = 0; i < 5; i++) {
            const starValue = child[i].getAttribute('data-value');
            if (starValue <= ratingRounded[l - 1]) {
                child[i].style.backgroundImage = `url('icon/star-full.png')`;
            } else if ((ratingRounded[l - 1] % (starValue - 1)) === 0.25) {
                child[i].style.backgroundImage = `url('icon/star-quater.png')`;
            } else if ((ratingRounded[l - 1] % (starValue - 1)) === 0.5) {
                child[i].style.backgroundImage = `url('icon/star-half.png')`;
            } else if ((ratingRounded[l - 1] % (starValue - 1)) === 0.75) {
                child[i].style.backgroundImage = `url('icon/star-3quater.png')`;
            } else {
                child[i].style.backgroundImage = `url('icon/star-empty.png')`;
            }
        }
    }
}

// Zaokrouhleni hodnoceni na nejblizsi ctvrtiny
function round(number) {
    var remainder = number % 0.25;
    if (remainder <= 0.12) {
        return Math.floor(number / 0.25) * 0.25;
    } else if (remainder <= 0.37) {
        return Math.floor(number / 0.25) * 0.25 + 0.25;
    } else if (remainder <= 0.62) {
        return Math.floor(number / 0.25) * 0.25 + 0.5;
    } else if (remainder <= 0.87) {
        return Math.floor(number / 0.25) * 0.25 + 0.75;
    } else {
        return Math.floor(number / 0.25) * 0.25 + 1.0;
    }
}