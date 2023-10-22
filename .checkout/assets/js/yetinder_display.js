const errorMessage = document.getElementById('error-message');
const yetiName = document.getElementById('yetiName');
const yetiPhoto = document.getElementById('yetiPhoto');
const yetiSex = document.getElementById('yetiSex');
const yetiAge = document.getElementById('yetiAge');
const yetiHeight = document.getElementById('yetiHeight');
const yetiWeight = document.getElementById('yetiWeight');
const yetiAddress = document.getElementById('yetiAddress');
const yetiDistance = document.getElementById('yetiDistance');
const card = document.getElementById('card');

window.i = 0;

// Zobrazeni konkretniho yetiho k hodnoceni
window.display = function (i, sortedArray) {
    if (sortedArray.length === 0) {
        error()
    } else {
        yetiName.innerText = sortedArray[i].yetiName;
        if (sortedArray[i].sex === 'male') {
            yetiSex.innerText = 'muž'
        } else {
            yetiSex.innerText = 'žena'
        }
        yetiPhoto.src = assetUrl + sortedArray[i].imageName;
        yetiAge.innerText = sortedArray[i].age + ' let';
        yetiHeight.innerText = sortedArray[i].height + ' cm';
        yetiWeight.innerText = sortedArray[i].weight + ' kg';
        yetiAddress.innerText = sortedArray[i].address;
        if (sortedArray[i].distance) {
            sortedArray[i].distance = Math.ceil(sortedArray[i].distance * 10) / 10;
            yetiDistance.innerText = "( " + sortedArray[i].distance + " km)";
        }

    }
}

window.updateDisplay = function () {
    i++;
    if (sortedArray.length <= i) {
        error();
    } else {
        display(i, sortedArray);
    }
}

function error() {
    errorMessage.textContent = 'Není zde co hodnotit - ohodnotili jste všechny yeti v databázi!';
    errorMessage.classList.remove("d-none");
    errorMessage.classList.add("d-block");
    card.classList.add("invisible");
}