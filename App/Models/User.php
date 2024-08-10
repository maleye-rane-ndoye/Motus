<?php
namespace App\Models;

class User extends DatabaseLog
{
    public $id;
    public $username;
    public $password;

    public static function findByUsername($username)
    {
        $db = new self();
        $pdo = $db->getBdd();
        $query = "SELECT * FROM users  WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch();

        if ($result) {
            $user = new User();
            $user->id = $result['id'];
            $user->username = $result['username'];
            $user->password = $result['password'];
            return $user;
        } else {
            return null;
        }
    }

    public function  save($username, $password) {
        try {
            $pdo = $this->getBdd();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashage du mot de passe
            $query = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$username, $hashedPassword]);
            return true; // Succès de l'insertion
        } catch (PDOException $e) {
            // Gérer les erreurs de requête SQL
            echo "Erreur SQL : " . $e->getMessage() . "<br>";
            echo "Requête SQL : " . $query . "<br>";
            echo "Paramètres : " . implode(", ", [$username, $password]) . "<br>";
            return false; // Échec de l'insertion
        }
    }

    public function login($username, $password) {
        try {
            $pdo = $this->getBdd();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                return $user; // user authentifié avec succès
            } else {
                return false; // Nom d'user ou mot de passe incorrect
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de sélection
            return false; // ou lancer une exception, etc.
        }
    }
}
