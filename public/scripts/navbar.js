document.querySelector('.hamburger-menu').addEventListener('click', function () {
    const navlinks = document.querySelector('.hamburger-menu .hamburger-navlinks');
    if (!navlinks.style.display || navlinks.style.display === 'none') {
        navlinks.style.display = 'flex';
    } else {
        navlinks.style.display = 'none';
    }
});