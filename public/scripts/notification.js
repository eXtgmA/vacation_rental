/**
 * This is just a helper function to add some simple notification
 * @type {HTMLDivElement}
 */

let notificationPopup = document.createElement("div");
notificationPopup.id = "notification-popup";
notificationPopup.classList.add("notification-popup");
notificationPopup.innerHTML = '<p>ACHTUNG!</p><button id="close-notification"><i class="fa-solid fa-xmark"></i></button>';
document.body.appendChild(notificationPopup);

/**
 * Close the notification popup
 * @type {HTMLElement}
 */
let closeNotificationButton = document.getElementById('close-notification');
closeNotificationButton.addEventListener('click', function() {
    notificationPopup.style.display = 'none';
});

/**
 * Show a notification popup
 * @param message
 * @param time (optional) in milliseconds
 */
function showNotification(message, time = 30000) {
    notificationPopup.querySelector('p').innerHTML = message;

    notificationPopup.style.display = 'block';

    setTimeout(function() {
        notificationPopup.style.display = 'none';
    }, time);

    notificationPopup.addEventListener('click', function() {
        notificationPopup.style.display = 'none';
    });
}