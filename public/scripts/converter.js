let dateValues = document.getElementsByClassName("date-value");

/**
 * change the date format from YYYY-MM-DD to DD.MM.YYYY
 * @param date
 * @returns {string}
 */
function formatDate(date)  {
    const _date = new Date(date);
    return (_date.getDate()).toString().padStart(2, '0') + "." + (_date.getMonth() + 1).toString().padStart(2, '0') + "." + _date.getFullYear();
}


for (let dateValue of dateValues) {
    dateValue.innerHTML = formatDate(dateValue.innerHTML);
    dateValue.addEventListener("change", function () {
        dateValue.innerHTML = formatDate(dateValue.innerHTML);
    });
}
