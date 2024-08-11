<?php
namespace App\Models;


class Word extends DatabaseLog
{
    // Déclare une propriété statique qui représente le nom de la table associée dans la base de données.
    protected static $table = 'words';

    // Méthode statique pour obtenir un mot aléatoire depuis la base de données.
    public static function getRandomWord()
    {
        // Crée une nouvelle instance de la classe Word (hérite de DatabaseLog).
        $db = new self();

        // Appelle la méthode getBdd() héritée de DatabaseLog pour obtenir l'objet PDO connecté à la base de données.
        $pdo = $db->getBdd();

        // Définie une requête SQL pour sélectionner un mot aléatoire depuis la table définie.
        $query = "SELECT * FROM " . self::$table . " ORDER BY RAND() LIMIT 1";

        // Exécute la requête SQL sur la base de données.
        $stmt = $pdo->query($query);

        // Récupère la première (et unique) ligne de résultat de la requête.
        $result = $stmt->fetch();

        // Si un mot a été trouvé, retourne-le en majuscules.
        // Si aucun mot n'a été trouvé, retourne null.
        return $result ? strtoupper($result['word']) : null;
    }
}
