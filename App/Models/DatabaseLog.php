<?php
namespace App\Models;

use PDO;
use PDOException;

/**
 * Classe abstraite DatabaseLog
 * 
 * Cette classe fournit une connexion à la base de données en utilisant PDO.
 * Les autres classes peuvent hériter de cette classe pour accéder à la base de données.
 */
abstract class DatabaseLog
{
    // Attribut pour stocker l'instance de PDO
    protected $pdo;

    /**
     * Constructeur
     * 
     * Le constructeur initialise la connexion à la base de données en appelant la méthode getBdd().
     */
    public function __construct()
    {
        // Initialisation de l'instance PDO via la méthode getBdd()
        $this->pdo = $this->getBdd();
    }

    /**
     * Méthode protégée getBdd
     * 
     * Cette méthode établit une connexion à la base de données et retourne l'objet PDO.
     *
     * @return PDO L'objet PDO pour interagir avec la base de données.
     */
    protected function getBdd()
    {
        // Paramètres de connexion à la base de données
        $host = "localhost"; // L'adresse du serveur de base de données
        $dbname = "motus";   // Le nom de la base de données
        $username = "root";  // Le nom d'utilisateur de la base de données
        $password = "";      // Le mot de passe pour l'utilisateur

        try {
            // Création d'une nouvelle instance PDO avec les paramètres de connexion
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

            // Définir le mode d'erreur de PDO à Exception pour gérer les erreurs SQL de manière plus propre
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Retourner l'objet PDO
            return $pdo;
        } catch (PDOException $e) {
            // En cas d'erreur lors de la connexion, afficher un message et arrêter l'exécution du script
            die("Connexion error ! : " . $e->getMessage());
        }
    }
}
