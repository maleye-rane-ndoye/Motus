<?php

namespace App\Models;

use PDO;

class Score extends DatabaseLog
{
    /**
     * Incrémente le nombre de victoires pour un utilisateur donné.
     *
     * @param int $userId L'identifiant de l'utilisateur dont on veut incrémenter le nombre de victoires.
     */
    public function incrementWin($userId)
    {
        // Prépare la requête SQL pour incrémenter le nombre de victoires de l'utilisateur spécifié.
        $stmt = $this->pdo->prepare("UPDATE score SET win = win + 1 WHERE id_user = :id_user");

        // Exécute la requête avec l'ID de l'utilisateur comme paramètre.
        $stmt->execute([':id_user' => $userId]);
    }

    /**
     * Incrémente le nombre de défaites pour un utilisateur donné.
     *
     * @param int $userId L'identifiant de l'utilisateur dont on veut incrémenter le nombre de défaites.
     */
    public function incrementLoss($userId)
    {
        // Prépare la requête SQL pour incrémenter le nombre de défaites de l'utilisateur spécifié.
        $stmt = $this->pdo->prepare("UPDATE score SET lost = lost + 1 WHERE id_user = :id_user");

        // Exécute la requête avec l'ID de l'utilisateur comme paramètre.
        $stmt->execute([':id_user' => $userId]);
    }

    /**
     * Crée un nouveau score pour un utilisateur donné avec des valeurs initiales de victoires et de défaites.
     *
     * @param int $userId L'identifiant de l'utilisateur pour lequel créer le score.
     * @param int $win Le nombre initial de victoires (par défaut 0).
     * @param int $lost Le nombre initial de défaites (par défaut 0).
     */
    public function createScore($userId, $win = 0, $lost = 0)
    {
        // Prépare la requête SQL pour insérer un nouveau score pour l'utilisateur spécifié.
        $stmt = $this->pdo->prepare("INSERT INTO score (id_user, win, lost) VALUES (:id_user, :win, :lost)");

        // Exécute la requête avec les paramètres fournis.
        $stmt->execute([
            ':id_user' => $userId,
            ':win' => $win,
            ':lost' => $lost
        ]);
    }

    /**
     * Vérifie si un utilisateur a déjà un score enregistré.
     *
     * @param int $userId L'identifiant de l'utilisateur à vérifier.
     * @return bool Retourne true si l'utilisateur a un score, false sinon.
     */
    public function checkScore($userId)
    {
        // Prépare la requête SQL pour vérifier l'existence d'un score pour l'utilisateur spécifié.
        $stmt = $this->pdo->prepare("SELECT * FROM score WHERE id_user = :id_user ");

        // Exécute la requête avec l'ID de l'utilisateur comme paramètre.
        $stmt->execute([':id_user' => $userId]);

        // Retourne true si l'utilisateur a un score enregistré (au moins une ligne trouvée), false sinon.
        return ($stmt->rowCount() > 0);
    }

    /**
     * Récupère le score (victoires et défaites) d'un utilisateur donné.
     *
     * @param int $userId L'identifiant de l'utilisateur dont on veut récupérer le score.
     * @return array|false Un tableau associatif contenant les victoires et défaites, ou false si aucun score n'est trouvé.
     */
    public function getScore($userId)
    {
        // Prépare la requête SQL pour récupérer les victoires et défaites de l'utilisateur spécifié.
        $stmt = $this->pdo->prepare("SELECT win, lost FROM score WHERE id_user = :id_user");

        // Exécute la requête avec l'ID de l'utilisateur comme paramètre.
        $stmt->execute([':id_user' => $userId]);

        // Retourne un tableau associatif avec les résultats ou false si aucun score n'est trouvé.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les scores de tous les utilisateurs avec pagination, triés par nombre de victoires décroissant et de défaites croissant.
     *
     * @param int $offset Le décalage pour la pagination (par défaut 0).
     * @param int $limit Le nombre maximum de résultats à retourner (par défaut 10).
     * @return array Un tableau associatif contenant les scores et les noms d'utilisateur.
     */
    public function getAllScores($offset = 0, $limit = 10)
    {
        // Prépare la requête SQL pour récupérer les scores de tous les utilisateurs avec pagination.
        // Les résultats sont triés par nombre de victoires décroissant et nombre de défaites croissant.
        $stmt = $this->pdo->prepare("SELECT u.username, s.win, s.lost FROM users u JOIN score s ON u.id = s.id_user ORDER BY s.win DESC, s.lost ASC LIMIT :offset, :limit");

        // Lie les paramètres de décalage et de limite pour la pagination.
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        // Exécute la requête SQL.
        $stmt->execute();

        // Retourne un tableau associatif avec les scores des utilisateurs.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
