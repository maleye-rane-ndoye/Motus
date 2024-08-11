// Sélectionne le formulaire de connexion par son ID
const formEl = document.getElementById('login-form');

// Sélectionne l'élément où le message d'erreur ou de succès sera affiché
const messageEl = document.getElementById('message');

// Ajoute un écouteur d'événement pour intercepter la soumission du formulaire
formEl.addEventListener('submit', async (event) => {
    event.preventDefault(); // Empêche le comportement par défaut de soumission du formulaire

    messageEl.textContent = ""; // Réinitialise le contenu du message

    // Récupère les données du formulaire en tant qu'objet FormData
    const formData = new FormData(event.target);

    // Envoie une requête POST asynchrone au serveur pour tenter de se connecter
    const request = await fetch("/motus/login", { 
        method: 'POST', 
        body: formData // Attache les données du formulaire à la requête
    });

    // Attend la réponse du serveur et la transforme en JSON
    const responseData = await request.json();

    // Affiche le message reçu du serveur (succès ou erreur) dans l'élément prévu à cet effet
    messageEl.textContent = responseData.message;
    console.log(responseData); // Log les données de réponse pour le débogage

    // Si l'authentification est réussie, redirige l'utilisateur vers la page du jeu
    if (responseData.status === "Autenticate") {
        window.location = "/motus/game"; // Redirection vers la page du jeu
    }
});
