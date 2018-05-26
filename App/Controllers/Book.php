<?php

namespace App\Controllers;

use \Core\View;
use \App\Configs\BookConfig;
use \App\Models\Genre;
use \App\Models\Author;
use \App\Models\Language;


class Book extends \Core\Controller
{
    public function listAction()
    {
        $query = array_key_exists('query', $_GET) ? $_GET['query'] : '';

        $books = \App\Models\Book::getList($query);
        View::renderTemplate('Book/list.html', [
            'query' => $query,
            'books' => $books
        ]);
    }

    public function viewAction()
    {
        $book = \App\Models\Book::getItem($this->routeParams['id']);

        if (!$book) {
            View::renderTemplate('404.html');
            return;
        }

        View::renderTemplate('Book/view.html', [
            'book' => $book
        ]);
    }

    public function addAction()
    {
        View::renderTemplate('Book/add.html', [
            'genres' => Genre::getAll(),
            'authors' => Author::getAll(),
            'languages' => Language::getAll()
        ]);
    }

    public function editAction()
    {
        $book = \App\Models\Book::getItem($this->routeParams['id']);

        if (!$book) {
            View::renderTemplate('404.html');
            return;
        }

        View::renderTemplate('Book/edit.html', [
            'book' => $book,
            'genres' => Genre::getAll(),
            'authors' => Author::getAll(),
            'languages' => Language::getAll()
        ]);
    }

    public function createAction()
    {
        $config = new BookConfig();
        $config->setTitle($_POST['title']);
        $config->setDescription($_POST['description']);
        $config->setGenre($_POST['genre']);
        $config->setAuthor($_POST['author']);
        $config->setLanguage($_POST['language']);
        $config->setPublicationDate($_POST['publication_date']);
        $config->setIsbn($_POST['isbn']);
        $config->setPicture($_FILES['picture']);

        $errors = $config->validate();

        if (count($errors)) {
            header('Location: /book/add?errors=' . urlencode(json_encode($errors)));
            return;
        }

        $insertedId = \App\Models\Book::create($config);

        header('Location: /book/view/' . $insertedId);
    }

    public function modifyAction()
    {
        $bookId = $_POST['id'];
        $book = \App\Models\Book::getItem($bookId);

        if (!$book) {
            View::renderTemplate('404.html');
            return;
        }

        $config = new BookConfig();
        $config->setTitle($_POST['title']);
        $config->setDescription($_POST['description']);
        $config->setGenre($_POST['genre']);
        $config->setAuthor($_POST['author']);
        $config->setLanguage($_POST['language']);
        $config->setPublicationDate($_POST['publication_date']);
        $config->setIsbn($_POST['isbn']);
        $config->setPicture($_FILES['picture']);

        $errors = $config->validate();

        if (count($errors)) {
            header('Location: /book/edit/' . $bookId . '?errors=' . urlencode(json_encode($errors)));
            return;
        }

        \App\Models\Book::modify($bookId, $config);

        header('Location: /book/view/' . $bookId);
    }

    public function deleteAction()
    {
        \App\Models\Book::deleteItem($this->routeParams['id']);
        header('Location: /book/list');
    }
}
