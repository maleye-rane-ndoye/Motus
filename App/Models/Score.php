<?php

namespace App\Models;

class Score extends DatabaseLog
{
    public function incrementWin($userId)
    {
        $stmt = $this->pdo->prepare("UPDATE score SET win = win + 1 WHERE id_user = :id_user");
        $stmt->execute([':id_user' => $userId]);
    }

    public function incrementLoss($userId)
    {
        $stmt = $this->pdo->prepare("UPDATE score SET lost = lost + 1 WHERE id_user = :id_user");
        $stmt->execute([':id_user' => $userId]);
    }

    public function createScore($userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO score (id_user, win, lost) VALUES (:id_user, 0, 0)");
        $stmt->execute([':id_user' => $userId]);
    }

    public function getScore($userId)
    {
        $stmt = $this->pdo->prepare("SELECT win, lost FROM score WHERE id_user = :id_user");
        $stmt->execute([':id_user' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllScores()
    {
        $stmt = $this->pdo->query("SELECT u.username, s.win, s.lost FROM users u JOIN score s ON u.id = s.id_user ORDER BY s.win DESC, s.lost ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
