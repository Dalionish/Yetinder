// Odchyt souradnic uzivatele
if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(function (position) {
        const userLatitude = position.coords.latitude;
        const userLongitude = position.coords.longitude;
        sortByDistance(userLatitude, userLongitude);
    }, function (error) {
        console.error("Error getting location: " + error.message);
        sortRandomly(yetis);
    });
} else {
    console.log("Geolocation is not supported in this browser.");
}

// Funkce pro spocitani geograficke vzdalenosti dle souradnic uzivatele a yetiho
function calculateDistance(userLatitude, userLongitude, yetiLatitude, yetiLongitude) {
    const R = 6371; // Polomer zeme v km

    const dLat = (yetiLatitude - userLatitude) * (Math.PI / 180);
    const dLon = (yetiLongitude - userLongitude) * (Math.PI / 180);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(userLatitude * (Math.PI / 180)) * Math.cos(yetiLatitude * (Math.PI / 180)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const distance = R * c;
    return distance;
}

// Serazeni yeti dle nejkratsi vzdalenosti k uzivateli
window.sortByDistance = function (userLatitude, userLongitude) {
    for (let l = 0; l < yetis.length; l++) {
        let yetiCoordinates = (yetis[l].coordinates).split(" ");
        let yetiLatitude = yetiCoordinates[0];
        let yetiLongitude = yetiCoordinates[1];
        let distance = calculateDistance(userLatitude, userLongitude, yetiLatitude, yetiLongitude);
        yetis[l].distance = distance;
    }
    window.sortedArray = yetis.sort((a, b) => a.distance - b.distance);
    display(i, sortedArray);
}


// Nahodne serazeni pokud uzivatel nepovoli pristup k poloze
function sortRandomly(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    window.sortedArray = array;
    display(i, sortedArray);
}