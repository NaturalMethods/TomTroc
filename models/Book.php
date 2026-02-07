<?php

/**
 * This class describe a book entity
 */
class Book extends AbstractEntity
{
    private int $idBook;
    private string $title;
    private string $author;
    private ?string $description;
    private int $disponibility;
    private int $idOwner;
    private string $owner;
    private string $bookImg;

    /**
     * Set the id book
     * @param int $idBook
     * @return void
     */
    public function setIdBook(int $idBook): void
    {
        $this->idBook = $idBook;
    }

    /**
     * Return the book id
     * @return int
     */
    public function getIdBook(): int
    {
        return $this->idBook;
    }

    /**
     * Set the book title
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Return the book title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Return the book author
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set the book author
     * @param string $author
     * @return void
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * Return the book description
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the book description
     * @param mixed $description
     * @return void
     */
    public function setDescription(mixed $description): void
    {
        if ($description != null)
            $this->description = $description;
        else $this->description = "La description de ce livre n'a pas encore été renseigné par le propriétaire.";
    }

    /**
     * Return the book disponibility
     * @return int
     */
    public function getDisponibility(): int
    {
        return $this->disponibility;
    }

    /**
     * Set the book disponibility
     * @param int $disponibility
     * @return void
     */
    public function setDisponibility(int $disponibility): void
    {
        $this->disponibility = $disponibility;
    }

    /**
     * Return the book owner id
     * @return int
     */
    public function getIdOwner(): int
    {
        return $this->idOwner;
    }

    /**
     * Set the book owner id
     * @param int $idOwner
     * @return void
     */
    public function setIdOwner(int $idOwner): void
    {
        $this->idOwner = $idOwner;
    }

    /**
     * Return the username of the owner
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * Set the username of the owner
     * @param string $owner
     * @return void
     */
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * Return the book image path
     * @return string
     */
    public function getBookImg(): string
    {
        return $this->bookImg;
    }

    /**
     * Set the book image path
     * @param string $bookImg
     * @return void
     */
    public function setBookImg(string $bookImg): void
    {
        $this->bookImg = $bookImg;
    }


}