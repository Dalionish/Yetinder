const previous = document.getElementById('previous');
const next = document.getElementById('next');
const firstPage = document.getElementById('firstPage');
const secondPage = document.getElementById('secondPage');
const thirdPage = document.getElementById('thirdPage')
const parent = document.getElementById('parent');
const optionDay = document.getElementById('24h');
const optionWeek = document.getElementById('7d');
const optionMonth = document.getElementById('30d');
const optionYear = document.getElementById('1y');
const optionAll = document.getElementById('all');
let storedValue = localStorage.getItem('selectedValue');
let nextPage = parseInt(currentPage) + 1;
let previousPage = parseInt(currentPage) - 1;

// Odchyt inputu od uzivatele a nasledne zobrazeni tabulky dle zvolene moznosti
document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('input');

    selectElement.addEventListener('change', function () {
        const userSelectedValue = selectElement.value;
        window.location.href = '/Statistics';
        localStorage.clear();
        localStorage.setItem('selectedValue', userSelectedValue);
        inputResolve(0, userSelectedValue);
    });
});

function inputResolve(page, input) {
    let currentTimestamp = Math.floor(Date.now() / 1000);
    switch (input) {
        case '24h':
            let day = currentTimestamp - 86400;
            displayTable(page, day);
            break;
        case '7d':
            let sevenDays = currentTimestamp - 604800;
            displayTable(page, sevenDays);
            break;
        case '30d':
            let month = currentTimestamp - 2592000;
            displayTable(page, month);
            break;
        case '1y':
            let year = currentTimestamp - 31536000;
            displayTable(page, year);
            break;
        case 'all':
            displayTable(page, 0);
    }
}

function displayTable(page, range) {
    for (let i = page * 50; i < (page * 50) + 50; i++) {
        if (data[i] && data[i].timestamp > range) {
            let tableRow = document.createElement("tr");
            parent.appendChild(tableRow);
            let tableCellDate = document.createElement("td");
            tableRow.appendChild(tableCellDate);
            let tableCellEmail = document.createElement("td");
            tableRow.appendChild(tableCellEmail);
            let tableCellYeti = document.createElement("td");
            tableRow.appendChild(tableCellYeti);
            let tableCellRating = document.createElement("td");
            tableRow.appendChild(tableCellRating);
            tableCellDate.innerText = data[i].date + ' | ' + data[i].time;
            tableCellEmail.innerText = data[i].email;
            tableCellYeti.innerText = data[i].yetiName;
            tableCellRating.innerText = data[i].rating + ' ⭐';
        } else {
            next.classList.add("disabled");
            if (currentPage === '1') {
                secondPage.classList.add("disabled");
                thirdPage.classList.add("disabled");
            } else if (currentPage === '2') {
                thirdPage.classList.add("disabled");
            }
            break;
        }
    }
}

// Udrzuje zvolenou hodnotu i po prechodu na dalsi stranky
switch (storedValue) {
    case '24h':
        optionDay.setAttribute('selected', 'selected');
        inputResolve(currentPage - 1, storedValue);
        break;
    case '7d':
        optionWeek.setAttribute('selected', 'selected');
        inputResolve(currentPage - 1, storedValue);
        break;
    case '30d':
        optionMonth.setAttribute('selected', 'selected');
        inputResolve(currentPage - 1, storedValue);
        break;
    case '1y':
        optionYear.setAttribute('selected', 'selected');
        inputResolve(currentPage - 1, storedValue);
        break;
    case 'all':
        optionAll.setAttribute('selected', 'selected');
        inputResolve(currentPage - 1, storedValue);
        break;
    default:
        displayTable(0, 0);
        optionAll.setAttribute('selected', 'selected')
}

// Nastaveni tlacitek dole na preklikavani mezi strankami
if (parseInt(currentPage) > 2) {
    setPagination(parseInt(currentPage));
} else {
    firstPage.setAttribute('href', href + '/' + 1);
    secondPage.setAttribute('href', href + '/' + 2);
    thirdPage.setAttribute('href', href + '/' + 3);
    if (currentPage === '1') {
        firstPage.classList.add("active");
        previous.classList.add("disabled");
    } else {
        secondPage.classList.add("active");
    }
}

next.setAttribute('href', href + '/' + nextPage);
previous.setAttribute('href', href + '/' + previousPage);

function setPagination(currentPage) {
    firstPage.innerText = currentPage - 2;
    secondPage.innerText = currentPage - 1;
    thirdPage.innerText = currentPage;

    firstPage.setAttribute('href', href + '/' + (currentPage - 2));
    secondPage.setAttribute('href', href + '/' + (currentPage - 1));
    thirdPage.setAttribute('href', href + '/' + currentPage);
    thirdPage.classList.add("active");
}