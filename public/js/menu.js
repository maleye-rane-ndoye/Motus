document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const menuOptions = document.getElementById('menu-options');

    menuToggle.addEventListener('click', function () {
        menuOptions.classList.toggle('visible');
    });

    document.getElementById('show-score-button').addEventListener('click', function () {
        window.location.href = '/motus/get-score';
    });

    document.getElementById('wall-of-fame-button').addEventListener('click', function () {
        window.location.href = '/motus/wall-of-fame';
    });

    document.getElementById('logout-button').addEventListener('click', function () {
        window.location.href = '/motus/logout';
    });
});
