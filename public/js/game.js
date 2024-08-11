// Attend que le contenu du DOM soit complètement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function () {
    
    let currentGuess = ''; // Variable pour stocker la devinette actuelle
    const customModalEl = document.getElementById("customModal"); // Référence à l'élément modal personnalisé pour les dialogues
    const confirmDialogEl = document.getElementById("confirmDialog"); // Référence au dialogue de confirmation
    const formEl = document.getElementById("guesses"); // Formulaire contenant les devinettes
    const dialogTitleEl = document.querySelector("#confirmDialog .dialog-title"); // Élément du titre du dialogue de confirmation
    const dialogMessageEl = document.querySelector("#confirmDialog .dialog-message"); // Élément du message du dialogue de confirmation
    
    const allGuessLetterInputs = document.querySelectorAll(".guess-letter"); // Sélectionne tous les champs de saisie des lettres
    
    let wordToGuess = ""; // Variable pour stocker le mot à deviner
    let currentFieldset = null; // Référence à l'ensemble de champs (fieldset) actuel
    let currentGuessWord = ""; // Variable pour stocker la devinette en cours
    let results = []; // Tableau pour stocker les résultats de la comparaison entre la devinette et le mot à deviner
    let currentFieldsetIndex = 0; // Indice de l'ensemble de champs actuel

    // Installer les écouteurs d'événements sur les champs de saisie des lettres
    installGuessLetterListeners();
    // Charger le mot à deviner depuis le serveur
    loadWordToGuess();
    // Activer le premier ensemble de champs (premier champ de saisie)
    enableFieldsetByIndex(currentFieldsetIndex);

    // Fonction pour installer les écouteurs d'événements sur les champs de saisie des lettres
    function installGuessLetterListeners() {
        allGuessLetterInputs.forEach((guessLetterInput) => {
            guessLetterInput.addEventListener("keyup", handleGuessLetter); // Écoute l'événement de relâchement d'une touche
            guessLetterInput.addEventListener("keypress", handleKeyPress); // Écoute l'événement d'appui sur une touche
        });
    }

    // Fonction pour charger le mot à deviner depuis le serveur
    async function loadWordToGuess() {
        const request = await fetch("/motus/word-to-guess"); // Envoie une requête pour obtenir le mot à deviner
        const responseData = await request.json(); // Convertit la réponse en JSON
        wordToGuess = responseData.word; // Stocke le mot à deviner
    }

    // Fonction pour gérer les pressions de touches dans les champs de saisie des lettres
    function handleKeyPress(event) {
        const regex = new RegExp("^[a-zA-Z]+$"); // Expression régulière pour vérifier si une touche est une lettre
        const code = event.charCode ? event.charCode : event.which; // Code de la touche pressée
        const letter = String.fromCharCode(code); // Convertit le code de la touche en lettre

        // Si la touche pressée n'est pas une lettre, empêcher l'entrée
        if (regex.test(letter) === false) {
            event.preventDefault();
            return;
        }

        console.log(`code is =>>> ${code} but letter =>>>> ${letter}`); // Log pour le débogage
    }

    // Fonction pour gérer l'événement de relâchement d'une touche dans les champs de saisie des lettres
    function handleGuessLetter(event) {
        const inputEl = event.target; // Référence à l'élément de saisie actuel
        const nextInputEl = inputEl.parentElement.nextElementSibling?.querySelector("input") ?? null; // Référence au champ de saisie suivant

        currentFieldset = inputEl.parentElement.parentElement; // Ensemble de champs actuel (fieldset)
        currentGuessWord = getWordByFieldset(currentFieldset); // Obtient la devinette actuelle

        const letter = inputEl.value; // Lettre entrée dans le champ de saisie

        console.log(`event is =>>> `, event); // Log pour le débogage

        // Si une lettre a été saisie et qu'il y a un champ de saisie suivant, déplacer le focus sur ce champ
        if ((letter.length === 1) && (nextInputEl !== null)) {
            nextInputEl.focus();
        } 

        // Si la devinette a 6 lettres, valider la devinette
        if (currentGuessWord.length === 6) {
            validateFieldset();
        }

        console.log('currentGuessWord is ', currentGuessWord); // Log pour le débogage
        console.log('inputEl is ', inputEl); // Log pour le débogage
    }

    // Fonction pour obtenir la devinette actuelle à partir de l'ensemble de champs actuel
    function getWordByFieldset(fieldsetEl) {
        const fieldsetInputs = fieldsetEl.querySelectorAll("input"); // Sélectionne tous les champs de saisie de l'ensemble
        let result = "";

        // Concatène les valeurs de tous les champs de saisie pour former le mot deviné
        fieldsetInputs.forEach((fieldsetInput) => {
            result += fieldsetInput.value;
        });

        return result.toUpperCase(); // Retourne le mot en majuscules
    }

    // Fonction pour valider la devinette actuelle
    function validateFieldset() {
        const letters = currentGuessWord.split(""); // Sépare la devinette actuelle en un tableau de lettres
        const splittedWordToGuess = wordToGuess.split(""); // Sépare le mot à deviner en un tableau de lettres
        const isWordMatch = currentGuessWord.trim().toLowerCase() === wordToGuess.trim().toLowerCase(); // Vérifie si la devinette correspond au mot à deviner
    
        // Compare chaque lettre de la devinette avec le mot à deviner
        results = letters.map((letter, index) => letter === splittedWordToGuess[index]);
    
        console.log(`i am validating the current fieldset now..., currentGuessWord => ${currentGuessWord} but wordToGuess => ${wordToGuess}`, results); // Log pour le débogage
        console.log(`words match ? => ${isWordMatch}`); // Log pour le débogage
        showResults(); // Affiche les résultats de la comparaison

        // Si la devinette est correcte, envoyer une requête pour mettre à jour le score et afficher un message de victoire
        if(isWordMatch){
            fetch('/motus/update-win', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message); // Log du message de succès
                    showConfirmDialog("Congratulations", "You have won the game; now your score is updated."); // Affiche un message de victoire
                });
            return; // Arrêter l'exécution si la devinette est correcte
        }
    
        // Activer l'ensemble de champs suivant si la devinette est incorrecte
        enableNextFieldset();
    }

    // Fonction pour activer l'ensemble de champs suivant
    function enableNextFieldset() {
        const nextFieldset = currentFieldset.nextElementSibling; // Référence à l'ensemble de champs suivant
    
        // Si aucun ensemble de champs suivant n'existe, envoyer une requête pour indiquer une défaite et afficher le mot à deviner
        if (nextFieldset == null) {
            fetch('/motus/update-loss', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message); // Log du message d'échec
                    showConfirmDialog("Game Over !!!", "The correct word to guess was... " + wordToGuess); // Affiche un message de défaite
                });
            return; // Arrêter l'exécution si le jeu est terminé
        }
    
        currentFieldsetIndex += 1; // Incrémente l'indice de l'ensemble de champs actuel
        enableFieldsetByIndex(currentFieldsetIndex); // Active le nouvel ensemble de champs
    
        // Si un ensemble de champs suivant existe, déplacer le focus sur le second champ de saisie de cet ensemble
        if (nextFieldset !== null) {
            const secondInputInFieldset = nextFieldset.querySelector(":scope > div:nth-child(2) input");
            secondInputInFieldset.focus();
        }
    }

    // Fonction pour afficher un dialogue de confirmation avec un titre et un message spécifiques
    function showConfirmDialog(title, message) {
        dialogTitleEl.textContent = title; // Met à jour le titre du dialogue
        dialogMessageEl.textContent = message; // Met à jour le message du dialogue
        customModalEl.setAttribute("opened", ""); // Ouvre le modal personnalisé
    }

    // Fonction pour cacher le dialogue de confirmation
    function hideConfirmDialog() {
        customModalEl.removeAttribute("opened", ""); // Ferme le modal personnalisé
    }


        // Fonction pour afficher les résultats de la devinette dans l'ensemble de champs actuel
    function showResults() {
        const allCurrentFieldsetInputs = currentFieldset.querySelectorAll("input"); // Sélectionne tous les champs de saisie de l'ensemble actuel

        // Parcourt chaque champ de saisie de l'ensemble actuel
        allCurrentFieldsetInputs.forEach((currentFieldsetInput, index) => {
            const currentInputValue = currentFieldsetInput.value.toUpperCase(); // Récupère la valeur entrée par l'utilisateur et la met en majuscule
            const isResultDefined = results[index] !== undefined; // Vérifie si le résultat pour cette lettre est défini

            // Si le résultat pour cette lettre n'est pas défini, on passe à la lettre suivante
            if (!isResultDefined) {
                return;
            }

            // Si la lettre est correcte (à la bonne position)
            if (results[index] === true) {
                currentFieldsetInput.parentElement.classList.add("correct"); // Ajoute la classe CSS "correct"
                currentFieldsetInput.parentElement.classList.remove("wrong"); // Supprime la classe CSS "wrong"
                currentFieldsetInput.parentElement.classList.remove("maybe"); // Supprime la classe CSS "maybe"
            } else {
                // Si la lettre est incorrecte (n'est pas à la bonne position ou absente)
                currentFieldsetInput.parentElement.classList.add("wrong"); // Ajoute la classe CSS "wrong"
                currentFieldsetInput.parentElement.classList.remove("correct"); // Supprime la classe CSS "correct"

                // Si la lettre fait partie du mot à deviner mais n'est pas à la bonne position
                if (wordToGuess.includes(currentInputValue)) {
                    currentFieldsetInput.parentElement.classList.add("maybe"); // Ajoute la classe CSS "maybe"
                }
            }
        });
    }

    // Fonction pour activer un ensemble de champs spécifique en fonction de son index
    function enableFieldsetByIndex(index) {
        const fieldsetToEnable = formEl.querySelector(`:scope > fieldset:nth-child(${index + 1})`); // Sélectionne l'ensemble de champs correspondant à l'index
        if (fieldsetToEnable) {
            fieldsetToEnable.removeAttribute("disabled"); // Enlève l'attribut "disabled" pour activer l'ensemble de champs
        }
    }

    // Fonction pour désactiver un ensemble de champs spécifique en fonction de son index
    function disableFieldsetByIndex(index) {
        const fieldsetToDisable = formEl.querySelector(`:scope > fieldset:nth-child(${index + 1})`); // Sélectionne l'ensemble de champs correspondant à l'index
        if (fieldsetToDisable) {
            fieldsetToDisable.setAttribute("disabled", ""); // Ajoute l'attribut "disabled" pour désactiver l'ensemble de champs
        }
    }

    // Fonction pour quitter le jeu et rediriger l'utilisateur vers la page principale
    function exitGame() {
        window.location = "/motus"; // Redirige l'utilisateur vers la page d'accueil du jeu
    }

    // Fonction pour redémarrer le jeu en rechargeant la page
    window.restartGame = function () {
        fetch('/motus/game?restart=true') // Envoie une requête au serveur pour redémarrer le jeu
        .then(() => {
            location.reload(); // Recharge la page pour réinitialiser le jeu
        });
    }

    // Fonction appelée lorsque l'utilisateur confirme le dialogue de confirmation
    window.onConfirmDialog = function () {
        restartGame(); // Redémarre le jeu
    }

    // Fonction appelée lorsque l'utilisateur annule le dialogue de confirmation
    window.onCancelDialog = function () {
        hideConfirmDialog(); // Masque le dialogue de confirmation
        exitGame(); // Quitte le jeu et redirige l'utilisateur vers la page principale
    }

    
});
