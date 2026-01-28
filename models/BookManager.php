<?php

class BookManager extends AbstractEntityManager
{

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

    public function getBookByID(int $idBook): ?Book
    {
        $sql = "SELECT  b.*, u.username as owner from books b join users u on b.idOwner = u.idUser where b.idBook = :idBook ;";
        $result = $this->db->query($sql, ['idBook' => $idBook]);
        $book = $result->fetch();
        if($book) {
            return new Book($book);
        }
        return null;
    }

    public function getUserBooks(int $idUser): ?array{

        $sql = "SELECT * from books where idOwner = :idUser;";
        $result = $this->db->query($sql, ['idUser' => $idUser]);
        $books = [];

        while ($book = $result->fetch())
            $books[] = new Book($book);

        return $books;
    }

}