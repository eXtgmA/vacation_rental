/**
 * This is just a helper function to add some simple notification
 * @type {HTMLDivElement}
 */

let notificationPopup = document.createElement("div");
notificationPopup.id = "notification-popup";
notificationPopup.classList.add("notification-popup");
notificationPopup.innerHTML = '<p>ACHTUNG!</p>';
document.body.appendChild(notificationPopup);

/**
 * Show a notification popup
 * @param message
 * @param time (optional)
 */
function showNotification(message, time = 30000) {
    notificationPopup.querySelector('p').innerHTML = message;

    notificationPopup.style.display = 'block';

    // Hide the notification popup after _time_ seconds
    setTimeout(function() {
        notificationPopup.style.display = 'none';
    }, time);
}