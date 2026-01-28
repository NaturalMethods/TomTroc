<?php

class BookController
{
    public function showHome(): void
    {
        $bookManager = new BookManager();
        $books = $bookManager->getLastFourBooks();

        $view = new View();
        $view->render("home", ['books' => $books]);

    }

    public function showBooks(): void
    {

        $bookManager = new BookManager();
        $books = $bookManager->getBooks();

        $view = new View();
        $view->render("books", ['books' => $books]);

    }

    public function showDetailBook(): void
    {

        $id = Utils::request("id", -1);

        $bookManager = new BookManager();
        $book = $bookManager->getBookByID($id);

        if (!$book) {
            throw new Exception("La page demandée n'existe pas.");
        }

        $view = new View();
        $view->render("detailbook", ['book' => $book]);
    }


}