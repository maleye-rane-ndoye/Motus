<?php

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\GameController;
use App\Controllers\WallOfFameController;

return function (App $app) {
    $app->get('/', GameController::class . ':index');
    $app->get('/register', AuthController::class . ':showRegisterForm');
    $app->post('/register', AuthController::class . ':register');
    $app->get('/login', AuthController::class . ':showLoginForm');
    $app->post('/login', AuthController::class . ':login');
    $app->get('/wall-of-fame', WallOfFameController::class . ':index');
};
