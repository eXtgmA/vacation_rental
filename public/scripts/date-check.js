let dateStartElement = document.getElementById("from-date-input-field");
let dateEndElement = document.getElementById("to-date-input-field");

/**
 * Compare two dates
 * Return -1 if firstDate < secondDate
 * Return 1 if firstDate > secondDate
 * Return 0 if firstDate == secondDate
 * @param firstDate
 * @param secondDate
 * @returns {number}
 */
function compareDates(firstDate, secondDate) {
    const millisecondsBetweenDates = secondDate - firstDate;
    if (millisecondsBetweenDates > 0) {
        console.debug("firstDate < secondDate");
        return -1;
    } else if (millisecondsBetweenDates < 0) {
        console.debug("firstDate > secondDate");
        return 1;
    } else {
        console.debug("firstDate == secondDate");
        return 0;
    }
}

dateStartElement.addEventListener("change", function (event) {
    let dateStart = new Date(event.target.value);
    let dateEnd = new Date(dateEndElement.value);

    // only do this date manipulation if dateStart is a Date object
    if (dateStart instanceof Date && !isNaN(dateStart)) {

        // startDate should be not earlier than today
        const today = new Date();
        if (compareDates(dateStart, today) < 0) {
            dateStartElement.value = today.toISOString().split("T")[0];
            dateStart = today;
        }

        // endDate should not be earlier than startDate
        if (!(dateEnd instanceof Date && !isNaN(dateEnd)) || compareDates(dateStart, dateEnd) >= 0) {
            // if endDate is not set, set it to startDate + 1 day
            dateEnd = dateStart;
            dateEnd.setDate(dateEnd.getDate() + 1);
            dateEndElement.value = (dateEnd).toISOString().split("T")[0];
        }
    }
});

dateEndElement.addEventListener("change", function (event) {
    let dateStart = new Date(dateStartElement.value);
    let dateEnd = new Date(event.target.value);

    // only do this date manipulation if dateEnd is a Date object
    if (dateEnd instanceof Date && !isNaN(dateEnd)) {

        // endDate should not be earlier than today + 1 day
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        if (compareDates(dateEnd, tomorrow) < 0) {
            dateEndElement.value = tomorrow.toISOString().split("T")[0];
            dateEnd = tomorrow;
        }

        // set startDate to endDate - 1 day if startDate is not set
        if (!(dateStart instanceof Date && !isNaN(dateStart)) || compareDates(dateStart, dateEnd) >= 0) {
            dateStart = dateEnd;
            dateStart.setDate(dateEnd.getDate() - 1);
            dateStartElement.value = (dateStart).toISOString().split("T")[0];
        }
    }
});