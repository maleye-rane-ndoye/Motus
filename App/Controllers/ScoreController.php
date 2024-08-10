<?php
namespace App\Controllers;

use App\Models\Score;

class ScoreController
{
    public function updateWin()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $scoreModel = new Score();
        $scoreModel->incrementWin($userId);

        echo json_encode(['status' => 'success', 'message' => 'Win updated successfully']);
    }

    public function updateLoss()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $scoreModel = new Score();
        $scoreModel->incrementLoss($userId);

        echo json_encode(['status' => 'success', 'message' => 'Loss updated successfully']);
    }

    public function getScore($userId)
    {
        $scoreModel = new Score();
        $score = $scoreModel->getScore($userId);
        echo json_encode($score);
    }

    public function showWallOfFame()
    {
        $scoreModel = new Score();
        $scores = $scoreModel->getAllScores();

        require 'views/wall_of_fame.php';
    }
}

