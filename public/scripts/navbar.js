document.querySelector('.sandwich-menu').addEventListener('click', function () {
    const navlinks = document.querySelector('.sandwich-menu .sandwich-navlinks');
    if (!navlinks.style.display || navlinks.style.display === 'none') {
        navlinks.style.display = 'flex';
    } else {
        navlinks.style.display = 'none';
    }
});