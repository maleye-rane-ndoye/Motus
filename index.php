<?php
// Active le rapport de toutes les erreurs PHP
error_reporting(E_ALL);
// Configure l'affichage des erreurs à l'écran
ini_set("display_errors", 1);

// Importation des contrôleurs nécessaires
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\GameController;
use App\Controllers\ScoreController;
use App\Controllers\WofController;

// Charge automatiquement les classes via Composer
require 'vendor/autoload.php';

// Initialise un nouvel objet AltoRouter pour gérer les routes
$router = new AltoRouter();
// Définit le chemin de base pour toutes les routes
$router->setBasePath('/motus');

// Démarre une session PHP
session_start();

// Définition des routes

// Route pour la page d'accueil
$router->map('GET', '/', function () {
	$homeController = new HomeController();
	$homeController->showHomePage();
}, 'showHomePage');

// Route pour la page des règles
$router->map('GET', '/rules', function () {
	$homeController = new HomeController();
	$homeController->showRulesPage();
}, 'showRulesPage');

// Route pour afficher la page de connexion
$router->map('GET', '/login', function () {
	$authController = new AuthController();
	$authController->showLoginPage();
}, 'showLoginPage');

// Route pour traiter la soumission du formulaire de connexion
$router->map('POST', '/login', function () {
	$authController = new AuthController();
	$authController->authenticate();
}, 'authenticate');

// Route pour afficher la page d'inscription
$router->map('GET', '/register', function () {
	$authController = new AuthController();
	$authController->showRegisterPage();
}, 'showRegisterPage');

// Route pour traiter la soumission du formulaire d'inscription
$router->map('POST', '/register', function () {
    $authController = new AuthController();
    $authController->storeUser();
}, 'storeUser');

// Route pour jouer au jeu
$router->map('GET', '/game', function () {
	$gameController = new GameController();
	$gameController->play();
}, 'play');

// Route pour obtenir le mot à deviner
$router->map('GET', '/word-to-guess', function () {
	$gameController = new GameController();
	$wordToGuess = $gameController->getWordToGuess();

	echo json_encode(["word" => $wordToGuess]);
}, 'word-to-guess');

// Route pour traiter une tentative de devinette
$router->map('POST', '/process-guess', function () {
    $gameController = new GameController();
    $gameController->processGuess();
}, 'processGuess');

// Route pour mettre à jour une victoire
$router->map('POST', '/update-win', function () {
    $scoreController = new ScoreController();
	$response = $scoreController->updateWin();

	echo json_encode($response);
}, 'updateWin');

// Route pour mettre à jour une défaite
$router->map('POST', '/update-loss', function () {
    $scoreController = new ScoreController();
	$response = $scoreController->updateLoss();

	echo json_encode($response);
}, 'updateLoss');

// Route pour afficher le "Mur de la renommée" avec pagination
$router->map('GET', '/wall-of-fame/[i:page]?', function ($page = 1) {
    $wofController = new WofController();
    $wofController->showPage($page);
}, 'wall-of-fame');

// Route pour obtenir tous les scores du "Mur de la renommée" avec pagination
$router->map('GET', '/all-wof/[i:page]?', function ($page = 1) {
    $wofController = new WofController();
    $response = $wofController->getAll($page);

	echo json_encode($response);
}, 'getScores');

// Route pour déconnecter l'utilisateur
$router->map('GET', '/logout', function () {
    $authController = new AuthController();
    $authController->logout();
}, 'logout');

// Correspond à la requête actuelle avec les routes définies
$match = $router->match();

// Appelle la fonction de la route correspondante ou renvoie une erreur 404
if (is_array($match) && is_callable($match['target'])) {
	call_user_func_array($match['target'], $match['params']); 
} else {
	// Aucune route n'a été trouvée
	header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}