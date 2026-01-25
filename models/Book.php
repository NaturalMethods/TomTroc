<?php

class Book extends AbstractEntity
{
    private int $idBook;
    private string $title;
    private string $author;
    private ?string $description;
    private int $disponibility;
    private string $owner;
    private string $bookImg;

    public function setIdBook(int $idBook): void
    {
        $this->idBook = $idBook;
    }
    public function getIdBook(): int
    {
        return $this->idBook;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(mixed $description): void
    {
        if($description != null)
            $this->description = $description;
        else $this->description = null;
    }

    public function getDisponibility(): int
    {
        return $this->disponibility;
    }
    public function setDisponibility(int $disponibility): void
    {
        $this->disponibility = $disponibility;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    public function getBookImg(): string
    {
        return $this->bookImg;
    }
    public function setBookImg(string $bookImg): void
    {
        $this->bookImg = $bookImg;
    }


}