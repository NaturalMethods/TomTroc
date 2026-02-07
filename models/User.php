<?php

class User extends AbstractEntity
{

    private int $idUser;
    private string $password;
    private string $username;
    private string $mail;
    private string $role;
    private string $date;
    private ?string $userPic;

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getCreatedAt(): string
    {
        return $this->date;
    }

    public function setCreatedAt(string $date): void
    {
        $this->date = $date;
    }

    public function getUserPic(): ?string
    {
        if($this->userPic)
            return $this->userPic;
        else return IMG."/damiers.png";

    }

    public function setUserPic(mixed $userPic): void
    {
        if($userPic)
            $this->userPic = $userPic;
        else $this->userPic = IMG."/damiers.png";
    }


}