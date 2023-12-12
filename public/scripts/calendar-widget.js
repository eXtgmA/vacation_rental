console.log("calendar-widget.js loaded");   // todo: remove

//   load default styles
const link = document.createElement('link');
link.rel = 'stylesheet';
link.href = '/styles/calendar-widget.css';
document.head.appendChild(link);

// Calendar Widget
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let weekdays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So']; // Start with Monday
let months = ['Januar', 'Februar', 'März', 'April', "Mai", "Juni", "Juli", "August", "September", "Oktober",
    "November", "Dezember"];
let bookedDays = ['2023-09-03', '2023-09-07', '2023-09-14', '2023-09-21', '2023-11-10', '2023-11-11', '2023-11-12',
    '2023-11-13', '2023-11-14', '2023-12-20', '2023-12-21', '2023-12-22', '2023-12-23', '2023-12-24', '2023-12-25',
    '2023-12-26', '2023-12-27'];

function drawCalendar(elementId, month, year, bookedDays = [])
{
    // remove spaces from elementId
    elementId = elementId.replace(/\s/g, '');

    console.log(elementId);
    let daysInMonth = new Date(year, month + 1, 0).getDate();
    let firstDayOfMonth = new Date(year, month, 1).getDay();
    let calendar = document.getElementById(elementId);
    calendar.innerHTML = `<div class="month" style="grid-column: span 7;">
                                 <button id="${elementId}-prev" class="btn-prev">&#8592;</button>
                                 <span style="font-weight: bolder"> ${months[month]} ${year}</span>
                                 <button id="${elementId}-next" class="btn-next">&#8594;</button>
                               </div>`;

    // Add the weekdays to the calendar
    for (let i = 0; i < weekdays.length; i++) {
        calendar.innerHTML += `<div class="weekday">${weekdays[i]}</div>`;
    }

    // Adjust the first day of the month for the new weekdays array
    firstDayOfMonth = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1;

    // Add empty days before the first day of the month
    for (let i = 0; i < firstDayOfMonth; i++) {
        calendar.innerHTML += `<div class="day"></div>`;
    }

    // Add the days of the month
    for (let i = 1; i <= daysInMonth; i++) {
        let currentDate = `${year}-${('0' + (month + 1)).slice(-2)}-${('0' + i).slice(-2)}`;
        // Check if the current day is a booked day
        if (bookedDays.includes(currentDate)) {
            // Add booked class to the day
            calendar.innerHTML += `<div class="day booked">${i}</div>`;
        } else {
            calendar.innerHTML += `<div class="day">${i}</div>`;
        }
    }

    // Add click events to the prev and next buttons
    document.getElementById(elementId + '-prev').onclick = function () {
        if (month === 0) {
            month = 11;
            year--;
        } else {
            month--;
        }
        drawCalendar(elementId, month, year, bookedDays);
    };

    // Add click events to the prev and next buttons
    document.getElementById(elementId + '-next').onclick = function () {
        if (month === 11) {
            month = 0;
            year++;
        } else {
            month++;
        }
        drawCalendar(elementId, month, year, bookedDays);
    };
}


