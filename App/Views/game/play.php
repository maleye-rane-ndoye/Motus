<!DOCTYPE html>
<html>
<head>
    <title>Jeu Motus</title>
    <link rel="stylesheet" href="./public/css/styles.css">
    <script src="./public/js/game.js" defer></script>
    <script src="./public/js/menu.js" defer></script>


</head>
<body>
    <h2>Motus Game</h2>
    <h3><?= $_SESSION['username']?></h3>

    <div class="menu">
        <button id="menu-toggle">Options</button>
        <div id="menu-options" class="hidden">
            <button id="new-try" type="button" onclick="restartGame()">New part</button>
            <button id="wall-of-fame-button">Wall of Fame</button>
            <button id="logout-button">Déconnexion</button>
        </div>
    </div>

    <div id="game-container">
        <form id="guesses">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['word_to_guess'])) {
                $_SESSION['word_to_guess'] = $word;
            }
            $wordToGuess = $_SESSION['word_to_guess'];
            ?>
            <?php for ($i = 0; $i < 6; $i++): ?>
                <fieldset class="guess-row" disabled>
                    <div class="input-wrapper first-letter">
                        <input class="guess-letter" value="<?= $wordToGuess[0] ?>" disabled />
                    </div>

                    <?php for ($j = 1; $j < strlen($wordToGuess); $j++): ?>
                        <div class="input-wrapper">
                            <input class="guess-letter" maxLength="1" minLength="1"/>
                        </div>

                    <?php endfor; ?>
                </fieldset>
            <?php endfor; ?>
        </form>
    </div>

    <div id="customModal">
        <span class="backdrop"></span>

        <div id="confirmDialog" class="dialog">
            <h3 class="dialog-title"></h3>
            <p class="dialog-message"></p>
            <div class="dialog-btns">
                <button onClick="onConfirmDialog()">Try Again</button>
                <button onClick="onCancelDialog()">Exit Game</button>
            </div>
        </div>
    </div>

</body>
</html>
