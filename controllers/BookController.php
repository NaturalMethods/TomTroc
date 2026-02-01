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

        $search = Utils::request("search");

        $bookManager = new BookManager();

        if (!empty($search))
            $books = $bookManager->getSearchLikeBooks($search);
        else
            $books = $bookManager->getBooks();

        $view = new View();
        $view->render("books", ['books' => $books]);

    }

    public function showDetailBook(): void
    {

        $id = Utils::request("id", -1);

        $bookManager = new BookManager();
        $book = $bookManager->getBookByID($id);

        if (!$book)
            Utils::redirect("books");

        $view = new View();
        $view->render("detailbook", ['book' => $book]);
    }

    public function showEditBook(): void
    {
        //TODO Vérifier que l'utilisateur connecté est le propriétaire du livre
        //TODO faire un chemin dans le config pour les CSS
        $bookManager = new BookManager();
        $book = $bookManager->getBookByID(Utils::request("id", -1));

        $view = new View();
        $view->render("editbook", ['book' => $book]);
    }


}