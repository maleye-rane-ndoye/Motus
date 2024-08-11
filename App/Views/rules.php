<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motus - Rules</title>
    <link rel="stylesheet" href="./public/css/styles.css">
</head>
<body>
<div class="contente">
    <div class="containers">
        <h1>How to Play Motus</h1>
        <p>Motus is a word guessing game where players try to guess a hidden word within a limited number of attempts. Here are the basic rules:</p>
        <ol>
            <li>The game selects a random word which the player has to guess.</li>
            <li>The player has a limited number of attempts to guess the word (usually six).</li>
            <li>For each guess, the game provides feedback:
                <ul>
                    <li>A letter in the correct position is highlighted in one red color.</li>
                    <li>A letter that is in the word but in the wrong position is highlighted in one yellow color.</li>
                    <li>A letter not in the word remains unhighlighted and stay blue.</li>
                </ul>
            </li>
            <li>The objective is to guess the word correctly within the allowed attempts.</li>
        </ol>
        <p>Ready to test your word skills? Sign up or sign in to start playing!</p>
        <div class="button-container">
            <a href="/motus/register" class="btn">Sign Up</a>
            <a href="/motus/login" class="btn">Sign In</a>
            <a href="/motus/" class="btn">Back to Home</a>
        </div>
    </div>
</div>
</body>
</html>
