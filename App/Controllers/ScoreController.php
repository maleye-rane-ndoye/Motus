<?php
namespace App\Controllers;

use App\Models\Score;

class ScoreController
{
    // Cette méthode met à jour le nombre de victoires pour un utilisateur.
    public function updateWin()
    {
        // Initialisation du tableau de résultats par défaut en cas d'erreur.
        $result = [
            "error" => true,
            "status" => 'failed',
            "message" => 'User not authenticated',
            "data" => [],
        ];

        // Vérifie si l'utilisateur est authentifié en vérifiant l'existence de 'user_id' dans la session.
        if (!isset($_SESSION['user_id'])) {
            return $result; // Retourne le résultat d'erreur si non authentifié.
        }

        // Récupère l'ID de l'utilisateur depuis la session.
        $userId = $_SESSION['user_id'];
        $scoreModel = new Score(); // Crée une instance du modèle Score.

        // Vérifie si un score existe déjà pour l'utilisateur.
        $userFound = $scoreModel->checkScore($userId);

        if ($userFound) {
            // Incrémente le nombre de victoires si l'utilisateur a déjà un score.
            $scoreModel->incrementWin($userId);
        } else {
            // Crée une nouvelle entrée de score avec une victoire et aucune défaite.
            $scoreModel->createScore($userId, 1, 0);
        }

        // Met à jour le résultat pour indiquer le succès de l'opération.
        $result["error"] = false;
        $result["status"] = 'success';
        $result["message"] = 'Win updated successfully';

        return $result; // Retourne le résultat de succès.
    }

    // Cette méthode met à jour le nombre de défaites pour un utilisateur.
    public function updateLoss()
    {
        // Initialisation du tableau de résultats par défaut en cas d'erreur.
        $result = [
            "error" => true,
            "status" => 'failed',
            "message" => 'User not authenticated',
            "data" => [],
        ];

        // Vérifie si l'utilisateur est authentifié.
        if (!isset($_SESSION['user_id'])) {
            return $result; // Retourne le résultat d'erreur si non authentifié.
        }

        // Récupère l'ID de l'utilisateur depuis la session.
        $userId = $_SESSION['user_id'];
        $scoreModel = new Score(); // Crée une instance du modèle Score.

        // Vérifie si un score existe déjà pour l'utilisateur.
        $userFound = $scoreModel->checkScore($userId);

        if ($userFound) {
            // Incrémente le nombre de défaites si l'utilisateur a déjà un score.
            $scoreModel->incrementLoss($userId);
        } else {
            // Crée une nouvelle entrée de score avec une défaite et aucune victoire.
            $scoreModel->createScore($userId, 1, 0);
        }

        // Met à jour le résultat pour indiquer le succès de l'opération.
        $result["error"] = false;
        $result["status"] = 'success';
        $result["message"] = 'Loss updated successfully';
        return $result; // Retourne le résultat de succès.
    }

    // Cette méthode récupère le score d'un utilisateur spécifique.
    public function getScore($userId)
    {
        $scoreModel = new Score(); // Crée une instance du modèle Score.
        $score = $scoreModel->getScore($userId); // Récupère le score de l'utilisateur.
        echo json_encode($score); // Renvoie le score sous forme de JSON.
    }

    // Cette méthode affiche le "Mur de la renommée" avec tous les scores.
    public function showWallOfFame()
    {
        $scoreModel = new Score(); // Crée une instance du modèle Score.
        $scores = $scoreModel->getAllScores(); // Récupère tous les scores.

        // Inclut le fichier de vue pour afficher le Mur de la renommée.
        require __DIR__ . './../Views/wall_of_fame.php';
    }
}