<?php

use JetBrains\PhpStorm\NoReturn;


/**
 *  Class controller about book page and book
 */
class BookController
{
    /**
     * Function called to display the homepage of the website
     * @return void
     */
    public function showHome(): void
    {
        $bookManager = new BookManager();
        $books = $bookManager->getLastFourBooks();

        $view = new View();
        $view->render("home", ['books' => $books], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);

    }

    /**
     * Function called to display the list of books page
     * @return void
     */
    public function showBooks(): void
    {
        $search = htmlspecialchars(Utils::request("search"));

        $bookManager = new BookManager();

        if (!empty($search))
            $books = $bookManager->getSearchLikeBooks($search);
        else
            $books = $bookManager->getBooks();

        $view = new View();
        $view->render("books", ['books' => $books], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);

    }

    /**
     * Function called to display details about a book
     * @return void
     */
    public function showDetailBook(): void
    {

        $id = htmlspecialchars(Utils::request("id", -1));

        $bookManager = new BookManager();
        $book = $bookManager->getBookByID($id);

        if (!$book)
            Utils::redirect("books");

        $userManager = new UserManager();
        $userPic = $userManager->getUserPicById($book->getIdOwner());

        if (!$userPic || !file_exists(USERS_IMAGES . $userPic))
            $userPic = "damiers.png";

        $view = new View();
        $view->render("detailbook", ['book' => $book, 'userPic' => $userPic], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);
    }

    /**
     * Check if the owner is the connected user
     * if no redirect to my account page
     * @param $idBook
     * @return void
     */
    public function checkIfIsBookOwner($idBook): void
    {
        $bookManager = new BookManager();
        $ownerID = $bookManager->getBookOwnerID($idBook);

        if ($_SESSION['idUser'] != $ownerID)
            Utils::redirect("myaccount");
    }

    /**
     * Function called to display the EditBook page
     * @return void
     */
    public function showEditBook(): void
    {
        Utils::checkIfUserIsConnected();

        $bookId = htmlspecialchars(Utils::request("id", -1));

        $this->checkIfIsBookOwner($bookId);

        $bookManager = new BookManager();
        $book = $bookManager->getBookByID($bookId);

        $view = new View();
        $view->render("editbook", ['book' => $book], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);
    }

    /**
     * Function called from the edit book page
     * Change information about a book if connected user is owner
     * @return void
     */
    #[NoReturn]
    public function changeBookInfos(): void
    {
        Utils::checkIfUserIsConnected();

        $bookId = htmlspecialchars(Utils::request("bookId"));
        $this->checkIfIsBookOwner($bookId);

        $bookManager = new BookManager();
        $currentBook = $bookManager->getBookByID($bookId);

        $bookTitle = htmlspecialchars(Utils::request("bookTitle", $currentBook->getTitle()));
        $bookAuthor = htmlspecialchars(Utils::request("bookAuthor", $currentBook->getAuthor()));
        $bookDescription = htmlspecialchars(Utils::request("bookCommentary", $currentBook->getDescription()));
        $bookDisponibility = htmlspecialchars(Utils::request("bookDisponibility", $currentBook->getDisponibility()));

        $book = [
            'idBook' => $bookId,
            'title' => $bookTitle,
            'author' => $bookAuthor,
            'description' => $bookDescription,
            'disponibility' => $bookDisponibility
        ];

        if (empty($book['title'])) $book['title'] = $currentBook->getTitle();
        if (empty($book['author'])) $book['author'] = $currentBook->getAuthor();
        if (empty($book['description'])) $book['description'] = "La description de ce livre n'a pas encore été renseigné par le propriétaire.";

        $bookManager->updateBookInfos(new Book($book));

        Utils::redirect("editbook", ['id' => $bookId]);

    }

    /**
     * Delete after confirmation a book from the "my account" page
     * @return void
     */
    #[NoReturn]
    public function deleteBook(): void
    {

        Utils::checkIfUserIsConnected();

        $bookId = htmlspecialchars(Utils::request("id"));
        $this->checkIfIsBookOwner($bookId);

        $bookManager = new BookManager();
        $bookManager->deleteBook($bookId);

        Utils::redirect("myaccount");

    }

    /**
     * Function called to upload a book image from "editbook" page
     * @return void
     */
    #[NoReturn]
    public function uploadBookPic(): void
    {

        Utils::checkIfUserIsConnected();

        $bookId = htmlspecialchars(Utils::request("bookId"));

        $this->checkIfIsBookOwner($bookId);

        $location = "editbook&id=" . $bookId;

        $bookManager = new BookManager();
        $book = $bookManager->getBookByID($bookId);
        $bookOldPic = $book->getBookImg();

        $name = Utils::savePicToDir($location, BOOKS_IMAGES);
        $bookNewPic = BOOKS_IMAGES . $name;

        if (file_exists($bookNewPic)) {
            if ($bookManager->setBookPicById($bookId, $name)) {
                Utils::deleteOldPic(BOOKS_IMAGES . $bookOldPic);
            } else {
                unlink($bookNewPic);
                Utils::redirect($location, ['error' => 'uploadError']);
            }
        }
        Utils::redirect($location);

    }

}