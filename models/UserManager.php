<?php

class UserManager extends AbstractEntityManager
{
    public function getUserById(int $idUser): ?User
    {

        $sql = "SELECT  * from users where idUser = :idUser ;";
        $result = $this->db->query($sql, ['idUser' => $idUser]);
        $user = $result->fetch();
        if ($user) {
            return new User($user);
        }
        return null;
    }

    public function getUserByUsername(string $username): ?User{

        $sql = "SELECT  * from users where username = :username ;";
        $result = $this->db->query($sql, ['username' => $username]);
        $user = $result->fetch();
        if ($user) {
            return new User($user);
        }
        return null;

    }

    public function getUserByMail(string $mail): ?User{

        $sql = "SELECT  * from users where mail = :mail ;";
        $result = $this->db->query($sql, ['mail' => $mail]);
        $user = $result->fetch();
        if ($user) {
            return new User($user);
        }
        return null;

    }

    public function setUserPicById(int $idUser,string $name): ?bool{

        $sql = "UPDATE users SET userPic = :name WHERE idUser = :idUser ;";
        $params = [':idUser' => $idUser,
                   ':name' => $name     ];

        $result = $this->db->query($sql, $params);

        if ($result->rowCount())
            return true;
        else return false;
    }

    public function getUserPicById(int $idUser): ?String{

        $sql = "SELECT  userPic from users where idUser = :idUser ;";
        $result = $this->db->query($sql, ['idUser' => $idUser]);
        $userPic = $result->fetch();
        if ($userPic) {
            return $userPic['userPic'];
        }
        return null;

    }

    public function modifyUserInfos(int $idUser, string $mail, string $username, string $password): bool
    {
        $fields = [];
        $params = [];

        if (!empty($mail)) {
            $fields[] = "mail = :mail";
            $params[':mail'] = $mail;
        }

        if (!empty($username)) {
            $fields[] = "username = :username";
            $params[':username'] = $username;
        }

        if (!empty($password)) {
            $fields[] = "password = :password";
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $params[':password'] = $hash;
        }

        if (!empty($fields)) {
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE idUser = :idUser ;";
            $params[':idUser'] = $idUser;

            $result = $this->db->query($sql, $params);

            if ($result->rowCount())
                return true;
            else return false;
        }

        return false;
    }

    public function registerUser(string $username, string $password, string $mail): ?int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, mail, password,role) VALUES (:username, :mail, :password, :role)";

        $params = [
            'username' => $username,
            'mail' => $mail,
            'password' => $hash,
            'role' => 'user',
        ];

        $result = $this->db->query($sql, $params);
        return $this->db->getPDO()->lastInsertId();
    }


}