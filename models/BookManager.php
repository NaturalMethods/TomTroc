<?php

/**
 *  This class describe interaction with the book table of database
 */
class BookManager extends AbstractEntityManager
{

    /**
     * Return the last four books added ordered by id (auto-increment)
     * @return array|null
     */
    public function getLastFourBooks(): ?array
    {
        $sql = "SELECT  b.idBook,
                        b.title,
                        b.author,
                        b.bookImg,
                        u.username AS owner
                FROM books b
                JOIN users u ON b.idOwner = u.idUser
                ORDER BY b.idBook DESC
                LIMIT 4;                                ";
        $result = $this->db->query($sql);
        $books = [];

        while ($book = $result->fetch()) {
            $books[] = new Book($book);
        }
        return $books;

    }

    /**
     * Return an array with all the books in database
     * @return array|null
     */
    public function getBooks(): ?array
    {
        $sql = "SELECT  *, u.username as owner from books b JOIN users u ON b.idOwner = u.idUser ORDER BY b.idBook DESC;";
        $result = $this->db->query($sql);
        $books = [];

        while ($book = $result->fetch()) {
            $books[] = new Book($book);
        }
        return $books;
    }

    /**
     * Return an array with books containing the $search in fields
     * @param $search
     * @return array|null
     */
    public function getSearchLikeBooks($search): ?array
    {
        $sql = "SELECT b.*, u.username as owner FROM books b join users u on b.idOwner = u.idUser where title LIKE :search 
                                      OR author LIKE :search 
                                      OR description LIKE :search 
                                      ";

        $result = $this->db->query($sql, ["search" => "%$search%"]);
        $books = [];

        while ($book = $result->fetch()) {
            $books[] = new Book($book);
        }
        return $books;
    }

    /**
     * Return a book by its ID or null if it doesn't exist
     * @param int $idBook
     * @return Book|null
     */
    public function getBookByID(int $idBook): ?Book
    {
        $sql = "SELECT  b.*, u.username as owner from books b join users u on b.idOwner = u.idUser where b.idBook = :idBook ;";
        $result = $this->db->query($sql, ['idBook' => $idBook]);
        $book = $result->fetch();
        if ($book) {
            return new Book($book);
        }
        return null;
    }

    /**
     * Return an array of book object owned by the user
     * @param int $idUser
     * @return array|null
     */
    public function getUserBooks(int $idUser): ?array
    {

        $sql = "SELECT * from books where idOwner = :idUser;";
        $result = $this->db->query($sql, ['idUser' => $idUser]);
        $books = [];

        while ($book = $result->fetch())
            $books[] = new Book($book);

        return $books;
    }

    /**
     * Return the id of the book owner
     * @param $idBook
     * @return int
     */
    public function getBookOwnerID($idBook): ?int
    {
        error_log("idbook:".$idBook);
        $sql = "SELECT idOwner from books where idBook = :idBook;";
        $result = $this->db->query($sql, ['idBook' => $idBook]);
        $ownerID = $result->fetch();

        if($ownerID)
        return $ownerID["idOwner"];

        return null;
    }
    public function setBookPicById(int $idBook, string $name): ?bool
    {
        $sql = "UPDATE books SET bookImg = :name WHERE idBook = :idBook ;";
        $params = [':idBook' => $idBook,
            ':name' => $name];

        $result = $this->db->query($sql, $params);

        if ($result->rowCount())
            return true;
        else return false;
    }


    /**
     * Update datas about a book
     * @param Book $book
     * @return void
     */
    public function updateBookInfos(Book $book): void
    {

        $sql = "UPDATE books SET title = :title, author = :author, description = :description, disponibility = :disponibility WHERE idBook = :idBook ;";

        $params = ['title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'description' => $book->getDescription(),
            'disponibility' => $book->getDisponibility(),
            'idBook' => $book->getIdBook()];

        $result = $this->db->query($sql, $params);

    }

    /**
     * Delete a book from database
     * @param int $idBook
     * @return void
     */
    public function deleteBook(int $idBook): void
    {
        $sql = "DELETE FROM books WHERE idBook = :idBook ;";

        $params = ['idBook' => $idBook];

        $result = $this->db->query($sql, $params);

    }

}