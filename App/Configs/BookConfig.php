<?php


namespace App\Configs;

use \App\Models\Genre;
use \App\Models\Author;
use \App\Models\Language;

class BookConfig {
    private $title;
    private $description;
    private $genre;
    private $author;
    private $language;
    private $publicationDate;
    private $isbn;
    private $picture;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param int $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    /**
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param int $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param string $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    public function validate()
    {
        $errors = [];

        if (strlen($this->title) === 0) {
            $errors []= 'You must specify a title';
        }

        if (strlen($this->description) === 0) {
            $errors []= 'You must specify a description';
        }

        if (!$this->isIdentifierValid(Genre::getAll(), $this->genre)) {
            $errors []= 'You must specify a valid genre';
        }

        if (!$this->isIdentifierValid(Author::getAll(), $this->author)) {
            $errors []= 'You must specify a valid author';
        }

        if (!$this->isIdentifierValid(Language::getAll(), $this->language)) {
            $errors []= 'You must specify a valid language';
        }

        if (!$this->isDateValid($this->publicationDate)) {
            $errors []= 'You must specify date in a valid format';
        }

        if (!$this->isIsbnValid($this->isbn)) {
            $errors []= 'You must enter a valid ISBN';
        }

        if (!$this->isPictureValid($this->picture)) {
            $errors []= 'Invalid book image. Only JPG, JPEG and PNG files are allowed.';
        }

        return $errors;
    }

    private function isDateValid($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }


    private function isIsbnValid($isbn)
    {
        $matchResult = preg_match('/^(?=.{17}$)97(?:8|9)([ -])\d{1,5}\1\d{1,7}\1\d{1,6}\1\d$/', trim($isbn));

        return $matchResult ? true : false;
    }

    private function isIdentifierValid($items, $id)
    {
        foreach ($items as $item) {
            if ((int)$item['id'] === (int)$id) {
                return true;
            }
        }

        return false;
    }

    private function isPictureValid($picture)
    {
        if (strlen($picture['tmp_name']) === 0) {
            $this->picture = false;
            return true;
        }

        $imageSize = getimagesize($picture['tmp_name']);
        if ($imageSize === false) {
            return false;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower(pathinfo($picture['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            return false;
        }

        $targetName = uniqid('img_') . '.' . $extension;
        $targetPath = BOOK_PICTURES_DIR . '/' . $targetName;
        $moveResult = move_uploaded_file($picture['tmp_name'], $targetPath);
        if (!$moveResult) {
            return false;
        }

        $this->picture['target_name'] = $targetName;

        return true;
    }
}