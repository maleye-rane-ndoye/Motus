<?php
namespace App\Models;


class User extends DatabaseLog
{
    // Déclaration des propriétés publiques pour stocker les informations d'un utilisateur.
    public $id;
    public $username;
    public $password;

    // Méthode statique pour trouver un utilisateur par son nom d'utilisateur.
    public static function findByUsername($username)
    {
        // Crée une nouvelle instance de la classe pour accéder à la base de données.
        $db = new self();

        // Récupère l'objet PDO pour la connexion à la base de données.
        $pdo = $db->getBdd();

        // Requête SQL pour sélectionner un utilisateur par son nom d'utilisateur.
        $query = "SELECT * FROM users WHERE username = :username";

        // Prépare la requête SQL pour éviter les injections SQL.
        $stmt = $pdo->prepare($query);

        // Exécute la requête avec le nom d'utilisateur passé en paramètre.
        $stmt->execute([':username' => $username]);

        // Récupère le premier résultat de la requête.
        $result = $stmt->fetch();

        // Si un utilisateur est trouvé, on remplit un objet User avec les données récupérées.
        if ($result) {
            $user = new User();
            $user->id = $result['id'];
            $user->username = $result['username'];
            $user->password = $result['password'];
            return $user; // Retourne l'objet User.
        } else {
            return null; // Aucun utilisateur trouvé.
        }
    }

    // Méthode publique pour enregistrer un nouvel utilisateur dans la base de données.
    public function save($username, $password) {
        try {
            // Récupère l'objet PDO pour la connexion à la base de données.
            $pdo = $this->getBdd();

            // Hashage du mot de passe pour des raisons de sécurité.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Requête SQL pour insérer un nouvel utilisateur avec son nom d'utilisateur et son mot de passe hashé.
            $query = "INSERT INTO users (username, password) VALUES (?, ?)";

            // Prépare la requête pour insertion.
            $stmt = $pdo->prepare($query);

            // Exécute la requête avec les données fournies.
            $stmt->execute([$username, $hashedPassword]);

            return true; // Indique que l'insertion a été effectuée avec succès.
        } catch (PDOException $e) {
            // Gérer les erreurs de requête SQL en affichant les messages d'erreur.
            echo "Erreur SQL : " . $e->getMessage() . "<br>";
            echo "Requête SQL : " . $query . "<br>";
            echo "Paramètres : " . implode(", ", [$username, $password]) . "<br>";
            return false; // Indique un échec de l'insertion.
        }
    }

    // Méthode publique pour authentifier un utilisateur lors de la connexion.
    public function login($username, $password) {
        try {
            // Récupère l'objet PDO pour la connexion à la base de données.
            $pdo = $this->getBdd();

            // Prépare une requête pour sélectionner un utilisateur par son nom d'utilisateur.
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");

            // Exécute la requête avec le nom d'utilisateur fourni.
            $stmt->execute([$username]);

            // Récupère les données de l'utilisateur trouvé.
            $user = $stmt->fetch();

            // Vérifie si un utilisateur est trouvé et si le mot de passe fourni correspond au mot de passe hashé dans la base de données.
            if ($user && password_verify($password, $user['password'])) {
                return $user; // Retourne les informations de l'utilisateur authentifié avec succès.
            } else {
                return false; // Retourne false si le nom d'utilisateur ou le mot de passe est incorrect.
            }
        } catch (PDOException $e) {
            // Gère les erreurs de requête SQL.
            return false; // Retourne false en cas d'erreur SQL.
        }
    }
}
