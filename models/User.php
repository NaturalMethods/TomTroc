<?php

/**
 *  This class describe a User entity
 */
class User extends AbstractEntity
{

    private int $idUser;
    private string $password;
    private string $username;
    private string $mail;
    private string $role;
    private string $date;
    private ?string $userPic;

    /**
     * Return the user id
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * Set the user id
     * @param int $idUser
     * @return void
     */
    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * Return the password of the user
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the password of the user
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Return the username
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the username
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Return the user mail
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * Set the user mail
     * @param string $mail
     * @return void
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * Return the user role
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set the user role
     * @param string $role
     * @return void
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * Return the date of creation of the user
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->date;
    }

    /**
     * Set the date of creation of the user
     * @param string $date
     * @return void
     */
    public function setCreatedAt(string $date): void
    {
        $this->date = $date;
    }

    /**
     * Return a string corresponding to the age of the account in years/month/day
     * @param string $createdAt
     * @return string
     * @throws Exception
     */
    public function memberDuration(): string
    {
        $created = new DateTime($this->getCreatedAt());
        $now = new DateTime();
        $diff = $created->diff($now);

        if ($diff->y >= 1) {
            return $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
        }

        if ($diff->m >= 1) {
            return $diff->m . ' mois';
        }

        if ($diff->d >= 1) {
            return $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
        }

        return 'aujourd’hui';
    }

    /**
     * Return the path of user image
     * @return string|null
     */
    public function getUserPic(): ?string
    {
        if ($this->userPic)
            return $this->userPic;
        else return IMG . "/damiers.png";

    }

    /**
     * Set the path of user image
     * @param mixed $userPic
     * @return void
     */
    public function setUserPic(mixed $userPic): void
    {
        if ($userPic)
            $this->userPic = $userPic;
        else $this->userPic = IMG . "/damiers.png";
    }


}