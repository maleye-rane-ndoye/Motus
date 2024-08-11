// Sélectionne le formulaire d'inscription et l'élément où les messages seront affichés
const formEl = document.getElementById('register-form');
const messageEl = document.getElementById('message');

// Ajoute un écouteur d'événement pour intercepter la soumission du formulaire
formEl.addEventListener('submit', async (event) => {
    // Empêche l'action par défaut du formulaire (qui serait de recharger la page)
    event.preventDefault();

    // Réinitialise le contenu du message à vide avant de traiter la soumission
    messageEl.textContent = "";

    // Crée un objet FormData à partir des données du formulaire soumis
    const formData = new FormData(event.target);

    // Envoie une requête POST asynchrone au serveur pour enregistrer un nouvel utilisateur
    const request = await fetch("/motus/register", { method:'POST', body: formData });

    // Attend la réponse du serveur en format JSON
    const responseData = await request.json();

    // Affiche le message de réponse du serveur dans l'élément prévu à cet effet
    messageEl.textContent = responseData.message;

    // Affiche la réponse complète dans la console (utile pour le débogage)
    console.log(responseData);

    // Si l'enregistrement est réussi, redirige l'utilisateur vers la page de connexion
    if (responseData.status === "registered") window.location = "/motus/login";
    
    // Réinitialise le formulaire pour vider tous les champs
    formEl.reset();
});
