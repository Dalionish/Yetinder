// Zobrazeni mapy podle pozice uzivatele
if (navigator.geolocation) {
    const successCallback = (position) => {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        setMapCords(latitude, longitude, 14);
    };

    const errorCallback = () => {
        setMapCords(49.8, 15.5, 7);
    };
    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
} else {
    setMapCords(49.8, 15.5, 7);
}


function setMapCords(latitude, longitude, zoom) {
    var map = L.map('map').setView([latitude, longitude], zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker;

    map.on('click', function (e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('add_form_latitude').value = e.latlng.lat;
        document.getElementById('add_form_longitude').value = e.latlng.lng;
        geoCode(e.latlng.lat, e.latlng.lng);
    });
}

// Dekodovani souradnic na konkretni adresu
function geoCode(latitude, longitude) {

    const geocoder = new google.maps.Geocoder();

    const latlng = new google.maps.LatLng(latitude, longitude);

    geocoder.geocode({location: latlng}, (results, status) => {
        if (status === "OK") {
            if (results[0]) {
                // Nekdy se nazev obce nachazi pod 'locality', nekdy pod 'postal_town, nekdy jako 'administrative_area_level_1'
                const city = results[0].address_components.find(
                    (component) => component.types.includes("locality"));
                const city2 = results[0].address_components.find(
                    (component) => component.types.includes("postal_town"));
                const city3 = results[0].address_components.find(
                    (component) => component.types.includes("administrative_area_level_1"));
                const country = results[0].address_components.find(
                    (component) => component.types.includes("country"));

                // V pripade CR se ulozi pouze nazev mesta (ci nejblizsi obce), mimo CR se ulozi i nazev zeme
                if (country.short_name === 'CZ') {
                    if (city) {
                        document.getElementById('add_form_address').value = city.long_name;
                    } else if (city2) {
                        document.getElementById('add_form_address').value = city2.long_name;
                    } else if (city3) {
                        document.getElementById('add_form_address').value = city3.long_name;
                    } else {
                        document.getElementById('add_form_address').value = 'Ocean';
                    }
                } else {
                    if (city) {
                        document.getElementById('add_form_address').value = city.long_name + ', ' + country.long_name;
                    } else if (city2) {
                        document.getElementById('add_form_address').value = city2.long_name + ', ' + country.long_name;
                    } else if (city3) {
                        document.getElementById('add_form_address').value = city3.long_name + ', ' + country.long_name;
                    } else {
                        document.getElementById('add_form_address').value = 'Ocean';
                    }
                }

            } else {
                console.log("No results found.");
            }
        } else {
            console.error(`Geocode failed: ${status}`);
        }
    });

}
