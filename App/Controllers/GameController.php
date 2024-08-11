<?php
namespace App\Controllers;

use App\Models\Word;

class GameController
{
    // Constructeur de la classe GameController
    public function __construct() {
        // Démarre une session si aucune session n'est active
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Méthode principale pour jouer au jeu
    public function play()
    {
        // Vérifie si l'utilisateur a demandé à recommencer une nouvelle partie
        if (isset($_GET['restart'])) {
            $this->restartGame();
            return; // Arrête l'exécution pour éviter de charger le jeu actuel
        }

        // Récupère un mot aléatoire depuis la base de données
        $word = Word::getRandomWord();

        // Initialise les variables de session pour une nouvelle partie si elles n'existent pas
        if (!isset($_SESSION['word_to_guess'])) {
            $_SESSION['word_to_guess'] = $word;
            $_SESSION['guesses'] = []; // Stocke les tentatives de l'utilisateur
            $_SESSION['feedback'] = []; // Stocke le retour sur les tentatives
            $_SESSION['game_status'] = null; // État du jeu (gagné ou perdu)
            $_SESSION['correct_word'] = null; // Mot correct si le joueur perd
        }

        // Charge la vue du jeu
        require __DIR__ . './../Views/game/play.php';
    }

    // Méthode pour traiter une tentative de l'utilisateur
    public function processGuess()
    {
        // Démarre une session si aucune session n'est active
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Récupère le mot à deviner et la tentative de l'utilisateur
        $wordToGuess = $_SESSION['word_to_guess'];
        $guess = strtoupper($_POST['guess']); // Convertit la tentative en majuscules

        // Assume une fonction pour vérifier la tentative et retourner un feedback
        $feedback = $this->checkGuess($wordToGuess, $guess);

        // Ajoute la tentative et le feedback à la session
        $_SESSION['guesses'][] = $guess;
        $_SESSION['feedback'][] = $feedback;

        // Vérifie si le joueur a gagné ou perdu
        $gameStatus = null;
        if ($guess === $wordToGuess) {
            $gameStatus = 'won';
            $_SESSION['game_status'] = 'won';
        } elseif (count($_SESSION['guesses']) >= 6) { // Limite de tentatives
            $gameStatus = 'lost';
            $_SESSION['game_status'] = 'lost';
            $_SESSION['correct_word'] = $wordToGuess;
        }

        // Retourne une réponse JSON avec le feedback et l'état du jeu
        echo json_encode([
            'feedback' => $feedback,
            'status' => $gameStatus,
            'correct_word' => isset($_SESSION['correct_word']) ? $_SESSION['correct_word'] : null
        ]);
        exit(); // Termine l'exécution du script
    }

    // Méthode pour recommencer une nouvelle partie
    public function restartGame()
    {
        // Démarre une session si aucune session n'est active
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Réinitialise les variables de session
        unset($_SESSION['word_to_guess']);
        unset($_SESSION['guesses']);
        unset($_SESSION['feedback']);
        unset($_SESSION['game_status']);
        unset($_SESSION['correct_word']);

        // Redirige vers la page de jeu pour commencer une nouvelle partie
        header('Location: /motus/game');
        exit(); // Termine l'exécution du script
    }

    // Méthode pour obtenir le mot à deviner
    public function getWordToGuess()
    {
        $result = $_SESSION['word_to_guess']; // Récupère le mot de la session
        return $result; // Retourne le mot
    }
}