<?php
namespace App\Models;

use PDO;
use PDOException;

abstract class DatabaseLog
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = $this->getBdd();
    }

    protected function getBdd()
    {
        $host = "localhost";
        $dbname = "motus";
        $username = "root";
        $password = "";

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Connexion error ! : " . $e->getMessage());
        }
    }
}
