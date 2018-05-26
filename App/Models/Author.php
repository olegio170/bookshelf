<?php

namespace App\Models;

use PDO;

class Author extends \Core\Model
{
    public static function getAll()
    {
        $stmt = static::getDB()->query('
            SELECT `id`, `name` FROM `author`
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
