<?php
namespace App\Controllers;

use App\Models\Word;

class GameController
{

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function play()
    {
        // Vérifier si l'utilisateur veut recommencer une nouvelle partie
        if (isset($_GET['restart'])) {
            $this->restartGame();
            return;
        }

        // Récupère un mot aléatoire depuis la base de données
        $word = Word::getRandomWord();

        // Stocke le mot à deviner en session
        // if (session_status() == PHP_SESSION_NONE) {
        //     session_start();
        // }
        if (!isset($_SESSION['word_to_guess'])) {
            $_SESSION['word_to_guess'] = $word;
            $_SESSION['guesses'] = [];
            $_SESSION['feedback'] = [];
            $_SESSION['game_status'] = null;
            $_SESSION['correct_word'] = null;
        }

        // Charge la vue du jeu avec le mot aléatoire
        require __DIR__ . './../Views/game/play.php';
    }

    public function processGuess()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $wordToGuess = $_SESSION['word_to_guess'];
        $guess = strtoupper($_POST['guess']);

        // Assume a function to check the guess and return feedback
        $feedback = $this->checkGuess($wordToGuess, $guess);

        $_SESSION['guesses'][] = $guess;
        $_SESSION['feedback'][] = $feedback;

        // Check for win or loss
        $gameStatus = null;
        if ($guess === $wordToGuess) {
            $gameStatus = 'won';
            $_SESSION['game_status'] = 'won';
        } elseif (count($_SESSION['guesses']) >= 6) {
            $gameStatus = 'lost';
            $_SESSION['game_status'] = 'lost';
            $_SESSION['correct_word'] = $wordToGuess;
        }

        // Return JSON response
        echo json_encode([
            'feedback' => $feedback,
            'status' => $gameStatus,
            'correct_word' => isset($_SESSION['correct_word']) ? $_SESSION['correct_word'] : null
        ]);
        exit();
    }

    public function restartGame()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Réinitialiser les variables de session
        unset($_SESSION['word_to_guess']);
        unset($_SESSION['guesses']);
        unset($_SESSION['feedback']);
        unset($_SESSION['game_status']);
        unset($_SESSION['correct_word']);

        // Rediriger vers la page de jeu pour commencer une nouvelle partie
        header('Location: /motus/game');
        exit();
    }

   

    public function getWordToGuess()
    {
        $result = $_SESSION['word_to_guess'];
        return $result;
    }


    
}
