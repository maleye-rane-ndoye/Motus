<?php
use Slim\Factory\AppFactory; //import de la class AppFactory pour creer une app avec le framwork slim
use Illuminate\Database\Capsule\Manager as Capsule; //import de la class Manager depuis illuminate, un manager de base de données et générateur de requetes expressif

require __DIR__ . '/../vendor/autoload.php';// inclure le fichier autolodeur de composer et touts les class necessaire pour gérer les dependances de l'app
require __DIR__ . '/../config/database.php';// inclure le fichier de configuration de la connexion entre la base de données et l'app

$app = AppFactory::create();//creer une nouvelle instance  de l'app en utilisant la methode AppFactory::create.
$app->addRoutingMiddleware();// attribué à l'app la methode de middleware pour les routes definis afin de les dispatcher
$errorMiddleware = $app->addErrorMiddleware(true, true, true);// affichages des erreus de lecture de routage et de connexion soit les trois parametres

//Routes
(require __DIR__ . '/../src/routes/web.php')($app);

$app->run();