<?php

namespace App\Models;

use PDO;

class Genre extends \Core\Model
{
    public static function getAll()
    {
        $stmt = static::getDB()->query('
            SELECT `id`, `name` FROM `genre`
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
