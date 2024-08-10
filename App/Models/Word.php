<?php
namespace App\Models;

class Word extends DatabaseLog
{
    protected static $table = 'words';

    public static function getRandomWord()
    {
        $db = new self();
        $pdo = $db->getBdd();
        $query = "SELECT * FROM " . self::$table . " ORDER BY RAND() LIMIT 1";
        $stmt = $pdo->query($query);
        $result = $stmt->fetch();

        return $result ? strtoupper($result['word']) : null;
    }


}
