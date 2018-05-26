<?php

namespace App\Models;

use PDO;

class Language extends \Core\Model
{
    public static function getAll()
    {
        $stmt = static::getDB()->query('
            SELECT `id`, `name`, `picture` FROM `language`
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
