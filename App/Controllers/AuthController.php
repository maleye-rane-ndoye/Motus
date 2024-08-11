<?php
namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function showRegisterPage()
    {
        require __DIR__ . './../Views/auth/register.php';
    }
    public function showLoginPage()
    {
        require __DIR__ . './../Views/auth/login.php';
    }

    public function storeUser() {
        $userLoggedIn = isset($_SESSION['user_id']);

        // Vérifier si le formulaire d'inscription a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les données du formulaire
        $username = $_POST['username'];
        $password = $_POST['password'];

            // Créer une instance du modèle User
            $userModel = new User();
                // Vérifier si le username existe déjà
            if ($userModel->findByUsername($username)) {
                // Afficher un message d'erreur
                echo json_encode([
                    'status' => "not-registered", 
                    'message' => "this user is allredy existe choos new user name.",
                    'error' => true, 
                ]);

                return; // Arrêter l'exécution de la méthode
            }

            // Appeler la méthode register du modèle User pour tenter l'inscription
            $success = $userModel->save($username, $password);

            if ($success) {
                echo json_encode([
                    'status' => "registered", 
                    'message' => "success registration",
                    'error' => false, 
                ]);
               // require __DIR__ . './../Views/auth/login.php';
            } else {
                echo json_encode([
                    'status' => "not-registered", 
                    'message' => "Error durring your registration",
                    'error' => true, 
                ]);
            }
        } else {
            // Afficher le formulaire d'inscription
            require __DIR__ . './../Views/auth/register.php';
        }
    }


    public function authenticate()
    {
     

        $userLoggedIn = isset($_SESSION['user_id']);

        // Vérifier si le formulaire de connexion a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les données du formulaire
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Créer une instance du modèle User
            $userModel = new User();

            // Appeler la méthode login du modèle User pour tenter la connexion
            $user = $userModel->login($username, $password);

            if ($user) {
                // Stocker des informations sur la session de l'utilisateur
                $_SESSION['user_id'] = $user['id'];
                if (isset($user['username'])) $_SESSION['username'] = $user['username'];
                if (isset($user['password'])) $_SESSION['userpassword'] = $user['password'];
                echo json_encode([
                    'status' => "Autenticate",
                    'message' => "You are successfully connected",
                    'error' => false,
                ]);

            } else {
                // Afficher un message d'erreur
                echo json_encode([
                    'status' => "not-connected",
                    'message' => "Error, try to enter the right username or password",
                    'error' => true,
                ]);
            }
        } else {
            // Afficher le formulaire de connexion
            require __DIR__ . './../Views/auth/login.php';
        }
    }

   

            public function logout()
        {
            session_destroy();
            header('Location: /motus');
            exit();
        }

}
