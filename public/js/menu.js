// Ajoute un écouteur d'événement pour exécuter le code lorsque le DOM est entièrement chargé
document.addEventListener('DOMContentLoaded', function () {

    // Sélectionne le bouton ou l'élément qui déclenche l'affichage ou le masquage du menu
    const menuToggle = document.getElementById('menu-toggle');

    // Sélectionne le conteneur qui contient les options du menu
    const menuOptions = document.getElementById('menu-options');

    // Ajoute un écouteur d'événement sur le bouton de menu pour gérer l'affichage/masquage du menu
    menuToggle.addEventListener('click', function () {
        // Alterne la classe 'visible' pour afficher ou masquer le menu
        menuOptions.classList.toggle('visible');
    });

    // Ajoute un écouteur d'événement sur le bouton "Wall of Fame" pour rediriger l'utilisateur vers la page correspondante
    document.getElementById('wall-of-fame-button').addEventListener('click', function () {
        window.location.href = '/motus/wall-of-fame'; // Redirection vers la page "Wall of Fame"
    });

    // Ajoute un écouteur d'événement sur le bouton "Logout" pour déconnecter l'utilisateur
    document.getElementById('logout-button').addEventListener('click', function () {
        window.location.href = '/motus/logout'; // Redirection vers la page de déconnexion
    });

    // Ajoute un écouteur d'événement sur le bouton "new part" pour rejouer une nouvelle partie
    document.getElementById('gamePage-button').addEventListener('click', function () {
        window.location.href = '/motus/game'; // Redirection vers la page de jeu
    });
});
