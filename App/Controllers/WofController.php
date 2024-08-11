<?php
namespace App\Controllers;

use App\Models\Score;

class WofController
{
    // Méthode pour obtenir le score d'un utilisateur spécifique
    public function getScore($userId)
    {
        $scoreModel = new Score(); // Crée une instance du modèle Score
        $score = $scoreModel->getScore($userId); // Récupère le score de l'utilisateur
        echo json_encode($score); // Renvoie le score sous forme de JSON
    }

    // Méthode pour obtenir tous les scores avec pagination
    public function getAll($page = 1)
    {
        // Calcule l'offset pour la pagination
        $offset = ($page - 1) * 10;
        $limit = 10; // Définit le nombre de résultats par page

        // Initialise le tableau de résultats
        $result = [
            "error" => false,
            "status" => '',
            "message" => '',
            "data" => [],
        ];

        $scoreModel = new Score(); // Crée une instance du modèle Score
        $score = $scoreModel->getAllScores($offset, $limit); // Récupère les scores avec l'offset et la limite

        $result["data"] = $score; // Stocke les scores récupérés dans le tableau de résultats

        return $result; // Retourne le tableau de résultats
    }

    // Méthode pour afficher une page spécifique du "Mur de la renommée"
    public function showPage($page = 1)
    {
        // Calcule l'offset pour la pagination
        $offset = ($page - 1) * 10;
        $limit = 10; // Définit le nombre de résultats par page

        $scoreModel = new Score(); // Crée une instance du modèle Score
        $scores = $scoreModel->getAllScores($offset, $limit); // Récupère les scores avec l'offset et la limite

        // Charge la vue du "Mur de la renommée" avec les scores récupérés
        require __DIR__ . './../Views/wall_of_fame.php';
    }
}