<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\GameController;
use App\Controllers\ScoreController;

require 'vendor/autoload.php';

$router = new AltoRouter();
$router->setBasePath('/motus');

// Define routes
$router->map('GET', '/', function () {
	$homeController = new HomeController();
	$homeController->showHomePage();
}, 'showHomePage');

$router->map('GET', '/rules', function () {
	$homeController = new HomeController();
	$homeController->showRulesPage();
}, 'showRulesPage');

$router->map('GET', '/login', function () {
	$authController = new AuthController();
	$authController->showLoginPage();
}, 'showLoginPage');

$router->map('POST', '/login', function () {
	$authController = new AuthController();
	$authController->authenticate();
}, 'Autenticate');

$router->map('GET', '/register', function () {
	$authController = new AuthController();
	$authController->showRegisterPage();
}, 'showRegisterPage');

$router->map('POST', '/register', function () {
    $authController = new AuthController();
    $authController->storeUser();
}, 'storeUser');

$router->map('GET', '/game', function () {
	$gameController = new GameController();
	$gameController->play();
}, 'play');

$router->map('GET', '/word-to-guess', function () {
	$gameController = new GameController();
	$wordToGuess = $gameController->getWordToGuess();

	echo json_encode(["word" => $wordToGuess]);

}, 'word-to-guess');

$router->map('POST', '/process-guess', function () {
    $gameController = new GameController();
    $gameController->processGuess();
}, 'processGuess');

$router->map('POST', '/update-win', function () {
    $scoreController = new ScoreController();
    $scoreController->updateWin();
}, 'updateWin');

$router->map('POST', '/update-loss', function () {
    $scoreController = new ScoreController();
    $scoreController->updateLoss();
}, 'updateLoss');

$router->map('GET', '/wall-of-fame', function () {
    $scoreController = new ScoreController();
    $scoreController->showWallOfFame();
}, 'wall-of-fame');

$router->map('GET', '/get-scores', function () {
    $scoreController = new ScoreController();
    $scoreController->getAllScores();
}, 'getScores');


$router->map('GET', '/logout', function () {
    $authController = new AuthController();
    $authController->logout();
}, 'logout');


// Match the current request
$match = $router->match();

// Call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
	call_user_func_array($match['target'], $match['params']); 
} else {
	// No route was matched
	header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
