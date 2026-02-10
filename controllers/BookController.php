<?php


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
        $search = Utils::request("search");

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

        $id = Utils::request("id", -1);

        $bookManager = new BookManager();
        $book = $bookManager->getBookByID($id);

        $userManager = new UserManager();
        $userPic = $userManager->getUserPicById($book->getIdOwner());

        if (!$userPic || !file_exists(USERS_IMAGES . $userPic))
            $userPic = "damiers.png";

        if (!$book)
            Utils::redirect("books");

        $view = new View();
        $view->render("detailbook", ['book' => $book, 'userPic' => $userPic], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);
    }

    /**
     * Check if the owner is the connected user
     * if no redirect to myaccount page
     * @param $idBook
     * @return void
     */
    public function checkIfIsBookOwner($idBook): void
    {

        $bookManager = new BookManager();
        $ownerID = $bookManager->getBookOwnerID($idBook);

        if ($_SESSION['idUser'] != $ownerID) {
            Utils::redirect("myaccount");
        }

    }

    /**
     * Function called to display the EditBook page
     * @return void
     */
    public function showEditBook(): void
    {
        //TODO Vérifier que l'utilisateur connecté est le propriétaire du livre
        //TODO faire un chemin dans le config pour les CSS

        UserController::checkIfUserIsConnected();

        $bookId = Utils::request("id", -1);

        $this->checkIfIsBookOwner($bookId);

        $bookManager = new BookManager();
        $book = $bookManager->getBookByID(Utils::request("id", -1));

        $view = new View();
        $view->render("editbook", ['book' => $book], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);
    }

    /**
     * Function called from the editbook page
     * Change information about a book if connected user is owner
     * @return void
     */
    public function changeBookInfos(): void
    {

        UserController::checkIfUserIsConnected();

        $bookId = Utils::request("bookId");
        $this->checkIfIsBookOwner($bookId);

        $bookManager = new BookManager();
        $currentBook = $bookManager->getBookByID($bookId);

        $bookTitle = Utils::request("bookTitle", $currentBook->getTitle());
        $bookAuthor = Utils::request("bookAuthor", $currentBook->getAuthor());
        $bookDescription = Utils::request("bookCommentary", $currentBook->getDescription());
        $bookDisponibility = Utils::request("bookDisponibility", $currentBook->getDisponibility());

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
     * Delete after confirmation a book from the "myaccount" page
     * @return void
     */
    public function deleteBook(): void
    {

        UserController::checkIfUserIsConnected();

        $bookId = Utils::request("id");
        $this->checkIfIsBookOwner($bookId);

        $bookManager = new BookManager();
        $bookManager->deleteBook($bookId);

        Utils::redirect("myaccount");

    }

}