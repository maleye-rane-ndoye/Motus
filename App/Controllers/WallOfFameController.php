<?php

namespace App\Controllers;

use App\Models\Score;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class WallOfFameController {
    protected $view;

    public function __construct() {
        $this->view = new PhpRenderer(__DIR__ . '/../views');
    }

    public function index(Request $request, Response $response, $args) {
        $scores = Score::orderBy('attempts', 'asc')->take(10)->get();
        return $this->view->render($response, 'wall_of_fame.php', ['scores' => $scores]);
    }
}
