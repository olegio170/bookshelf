<?php

namespace App\Models;

use App\Configs\BookConfig;
use PDO;

class Book extends \Core\Model
{
    public static function getList($query = '')
    {
        $stmt = static::getDB()->prepare('
            SELECT
                `title`, `description`, `isbn_number`, `publication_date`,
                `book`.`id` AS `id`,
                `book`.`picture` AS `picture`,
                `genre`.`name` AS `genre`,
                `author`.`name` AS `author`,
                `language`.`name` AS `language`,
                `language`.`picture` AS `language_picture`,
                `genre_id`,
                `author_id`,
                `language_id`
            FROM
              `book`
            LEFT JOIN
              `genre` ON `genre_id` = `genre`.`id`
            LEFT JOIN
              `author` ON `author_id` = `author`.`id`
            LEFT JOIN
              `language` ON `language_id` = `language`.`id`
            WHERE
                `title` LIKE ?
            ORDER BY
                `title` ASC
        ');

        $stmt->execute(['%' . $query . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getItem($id)
    {
        $stmt = static::getDB()->prepare('
            SELECT
                `title`, `description`, `isbn_number`, `publication_date`,
                `book`.`id` AS `id`,
                `book`.`picture` AS `picture`,
                `genre`.`name` AS `genre`,
                `author`.`name` AS `author`,
                `language`.`name` AS `language`,
                `language`.`picture` AS `language_picture`,
                `genre_id`,
                `author_id`,
                `language_id`
            FROM
              `book`
            LEFT JOIN
              `genre` ON `genre_id` = `genre`.`id`
            LEFT JOIN
              `author` ON `author_id` = `author`.`id`
            LEFT JOIN
              `language` ON `language_id` = `language`.`id`
            WHERE
              `book`.`id` = ?
        ');

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function deleteItem($id)
    {
        $book = static::getItem($id);
        if (!$book) {
            return;
        }

        $stmt = static::getDB()->prepare('
            DELETE FROM `book` WHERE `id` = ?
        ');

        $stmt->execute([$id]);

        $picturePath = BOOK_PICTURES_DIR . '/' . $book['picture'];
        if (strlen($book['picture']) && file_exists($picturePath)) {
            unlink($picturePath);
        }
    }

    public static function create(BookConfig $config)
    {
        $db = static::getDB();

        $stmt = $db->prepare('
            INSERT INTO
                `book` (
                  `title`, `description`, `isbn_number`, `publication_date`,
                  `picture`, `genre_id`, `author_id`, `language_id`
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $config->getTitle(),
            $config->getDescription(),
            $config->getIsbn(),
            $config->getPublicationDate(),
            $config->getPicture()['target_name'],
            $config->getGenre(),
            $config->getAuthor(),
            $config->getLanguage()
        ]);

        return $db->lastInsertId();
    }

    public static function modify($id, BookConfig $config)
    {
        $db = static::getDB();
        $book = static::getItem($id);

        $picture = $config->getPicture();
        $pictureName = $picture ? $picture['target_name'] : $book['picture'];

        $stmt = $db->prepare('
            UPDATE
                `book`
            SET
                `title` = ?, `description` = ?, `isbn_number` = ?, `publication_date` = ?,
                `picture` = ?, `genre_id` = ?, `author_id` = ?, `language_id` = ?
            WHERE
                `id` = ?
        ');

        $picturePath = BOOK_PICTURES_DIR . '/' . $book['picture'];
        if (strlen($picture['target_name']) && file_exists($picturePath)) {
            unlink($picturePath);
        }

        $stmt->execute([
            $config->getTitle(),
            $config->getDescription(),
            $config->getIsbn(),
            $config->getPublicationDate(),
            $pictureName,
            $config->getGenre(),
            $config->getAuthor(),
            $config->getLanguage(),
            $id
        ]);
    }
}
