document.addEventListener('DOMContentLoaded', function () {
    let currentGuess = ''; 
    const customModalEl = document.getElementById("customModal");
    const confirmDialogEl = document.getElementById("confirmDialog");
    const formEl = document.getElementById("guesses");
    const dialogTitleEl = document.querySelector("#confirmDialog .dialog-title");
    const dialogMessageEl = document.querySelector("#confirmDialog .dialog-message");
    
    const allGuessLetterInputs = document.querySelectorAll(".guess-letter");

    let wordToGuess = "";
    let currentFieldset = null;
    let currentGuessWord = "";
    let results = [];
    let currentFieldsetIndex = 0;

    installGuessLetterListeners();
    loadWordToGuess();
    enableFieldsetByIndex(currentFieldsetIndex);

    function installGuessLetterListeners() {
        allGuessLetterInputs.forEach((guessLetterInput) => {
            guessLetterInput.addEventListener("keyup", handleGuessLetter);
        })
    }

    async function loadWordToGuess() {
        const request = await fetch("/motus/word-to-guess");
        const responseData = await request.json();
        wordToGuess = responseData.word;
    }

    function handleGuessLetter(event) {
        const inputEl = event.target;
        const nextInputEl = inputEl.parentElement.nextElementSibling?.querySelector("input") ?? null;

        currentFieldset = inputEl.parentElement.parentElement;
        currentGuessWord = getWordByFieldset(currentFieldset);

        const letter = inputEl.value;

        if (/^[a-zA-Z]+$/.test(letter) === false) {
            inputEl.value = "";
            alert(`${letter} is not valid. Please enter only letters from A to Z`)
            return;
        }

        if ((letter.length === 1) && (nextInputEl !== null)) {
            nextInputEl.focus();
        } 

        if (currentGuessWord.length === 6) {
            validateFieldset();
        }

        console.log('currentGuessWord is ', currentGuessWord);
        console.log('inputEl is ', inputEl);
    }

    function getWordByFieldset(fieldsetEl) {
        const fieldsetInputs = fieldsetEl.querySelectorAll("input")
        let result = "";

        fieldsetInputs.forEach((fieldsetInput) => {
            result += fieldsetInput.value;
        })

        return result.toUpperCase();
    }

    function validateFieldset() {
        const letters = currentGuessWord.split("");
        const splittedWordToGuess = wordToGuess.split("");
        const isWordMatch = currentGuessWord.trim().toLowerCase() === wordToGuess.trim().toLowerCase();
    
        results = letters.map((letter, index) => letter === splittedWordToGuess[index]);
    
        console.log(`i am validating the current fieldset now..., currentGuessWord => ${currentGuessWord} but wordToGuess => ${wordToGuess}`, results);
        console.log(`words match ? => ${isWordMatch}`);
        showResults();
        if(isWordMatch){
            fetch('/motus/update-win', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    showConfirmDialog("Congratulations", "You have won the game; now your score is updated.");
                })
               
            return;
        }
    
        enableNextFieldset();
    }

    function enableNextFieldset() {
        const nextFieldset = currentFieldset.nextElementSibling;
    
        if (nextFieldset == null) {
            fetch('/motus/update-loss', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    showConfirmDialog("Game Over !!!", "The correct word to guess was... " + wordToGuess);
                })
                
            return;
        }
    
        currentFieldsetIndex += 1;
        enableFieldsetByIndex(currentFieldsetIndex);
    
        if (nextFieldset !== null) {
            const secondInputInFieldset = nextFieldset.querySelector(":scope > div:nth-child(2) input");
            secondInputInFieldset.focus();
        }
    }

    function showConfirmDialog(title, message) {
        dialogTitleEl.textContent = title;
        dialogMessageEl.textContent = message;
        customModalEl.setAttribute("opened", "");
    }

    function hideConfirmDialog() {
        customModalEl.removeAttribute("opened", "");
    }

    function showResults() {
        const allCurrentFieldsetInputs = currentFieldset.querySelectorAll("input");

        allCurrentFieldsetInputs.forEach((currentFieldsetInput, index) => {
            const currentInputValue = currentFieldsetInput.value.toUpperCase();
            const isResultDefined = results[index] !== undefined;

            if (!isResultDefined) {
                return;
            }
            
            if (results[index] === true) {
                currentFieldsetInput.parentElement.classList.add("correct");
                currentFieldsetInput.parentElement.classList.remove("wrong");
                currentFieldsetInput.parentElement.classList.remove("maybe");
            } else {
                currentFieldsetInput.parentElement.classList.add("wrong");
                currentFieldsetInput.parentElement.classList.remove("correct");

                if (wordToGuess.includes(currentInputValue)) {
                    currentFieldsetInput.parentElement.classList.add("maybe");
                }
            }
        });
    }

    function enableFieldsetByIndex(index) {
        const fieldsetToEnable = formEl.querySelector(`:scope > fieldset:nth-child(${index + 1})`);
        if (fieldsetToEnable) {
            fieldsetToEnable.removeAttribute("disabled");
        }
    }

    function disableFieldsetByIndex(index) {
        const fieldsetToDisable = formEl.querySelector(`:scope > fieldset:nth-child(${index + 1})`);
        if (fieldsetToDisable) {
            fieldsetToDisable.setAttribute("disabled", "");
        }
    }

    function exitGame() {
        window.location = "/motus";
    }

    window.restartGame = function () {
        fetch('/motus/game?restart=true')
        .then(() => {
            location.reload();
        });
    }

    window.onConfirmDialog = function () {
        restartGame();
    }

    window.onCancelDialog = function () {
        hideConfirmDialog();
        exitGame();
    }
    
});
