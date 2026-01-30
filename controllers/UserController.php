<?php

class UserController
{

    private function checkIfUserIsConnected(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['idUser'])) {
            Utils::redirect("connect");
        }
    }

    private function checkIfUserIsNotConnected(): void
    {
        if (isset($_SESSION['idUser']))
            Utils::redirect("myaccount");
    }

    private function checkIfUsernameExists(string $username): bool
    {
        $userManager = new UserManager();
        $user = $userManager->getUserByUsername($username);

        if ($user)
            return true;
        return false;
    }

    private function checkIfEmailExists(string $mail): bool
    {
        $userManager = new UserManager();
        $user = $userManager->getUserByMail($mail);

        if ($user)
            return true;
        return false;
    }

    private function checkChangeInUserInfos(string $location, string $username, string $mail, string $password): void
    {
        if (!empty($username) and $this->checkIfUsernameExists($username))
            Utils::redirect($location, ['error' => 'usernameExists']);

        if (!empty($mail) and $this->checkIfEmailExists($mail))
            Utils::redirect($location, ['error' => 'emailExists']);

    }

    private function checkUserInfos(string $location, string $username, string $mail, string $password): void
    {

        if (empty($username) || empty($mail) || empty($password))
            Utils::redirect($location, ['error' => 'emptyFields']);

        if ($this->checkIfUsernameExists($username))
            Utils::redirect($location, ['error' => 'usernameExists']);

        if ($this->checkIfEmailExists($mail))
            Utils::redirect($location, ['error' => 'emailExists']);

    }

    private function setErrorMessage(): ?array
    {
        $error = Utils::request("error", "");
        $errorMessage = [
            'emptyFieldMessage' => "hidden",
            'emailExistsMessage' => "hidden",
            'usernameExistsMessage' => "hidden",
        ];
        if ($error == "emptyFields")
            $errorMessage['emptyFieldMessage'] = "";

        if ($error == "emailExists")
            $errorMessage['emailExistsMessage'] = "";

        if ($error == "usernameExists")
            $errorMessage['usernameExistsMessage'] = "";

        return $errorMessage;
    }

    private function checkPassword(User $user,string $password): void {

        if (!password_verify($password, $user->getPassword())) {
            Utils::redirect("connect", ['error' => 'wrongIDs']);
        }

    }

    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $mail = Utils::request("userMail");
        $password = Utils::request("userPassword");

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByMail($mail);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        //TODO HASHER le mot de passe aussi dans la base donnéé + regenerer sessID eviter la fixation
        // On vérifie que le mot de passe est correct.

        $this->checkPassword($user, $password);

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getIdUser();

        // On redirige vers la page d'administration.
        Utils::redirect("home");
    }

    public function disconnectUser(): void
    {
        session_unset();
        session_destroy();

        header('Location: index.php?action=home');
        exit;
    }

    public function showMyAccount(): void
    {
        $this->checkIfUserIsConnected();

        $id = $_SESSION['idUser'];

        $errorMessage = $this->setErrorMessage();

        $userManager = new UserManager();
        $user = $userManager->getUserByID($id);


        $bookManager = new BookManager();
        $books = $bookManager->getUserBooks($user->getIdUser());

        if (!$user)
            throw new Exception("L'utilisateur n'existe pas.");

        if (!$user->getUserPic())
            $user->setUserPic("damiers.png");

        $view = new View();
        $view->render("account", ['user' => $user, 'books' => $books, 'errorMessage' => $errorMessage]);
    }

    public function showPublicAccount(): void
    {

        $id = Utils::request("id");

        $userManager = new UserManager();
        $user = $userManager->getUserByID($id);

        $bookManager = new BookManager();
        $books = $bookManager->getUserBooks($user->getIdUser());

        if (!$user)
            throw new Exception("L'utilisateur n'existe pas.");

        if (!$user->getUserPic())
            $user->setUserPic("damiers.png");

        $view = new View();
        $view->render("publicaccount", ['user' => $user, 'books' => $books]);

    }

    public function showRegister(): void
    {
        $this->checkIfUserIsNotConnected();

        $errorMessage = $this->setErrorMessage();

        $view = new View();
        $view->render("register", ['errorMessage' => $errorMessage]);
    }

    public function showConnect(): void
    {
        $this->checkIfUserIsNotConnected();

        $errorMessage = 'hidden';
        $error = Utils::request('error');
        if($error == "wrongIDs")
            $errorMessage = '';

        $view = new View();
        $view->render("connect", ['errorMessage' => $errorMessage]);
    }

    public function changeUserInfos(): void
    {

        $userId = Utils::request("id", -1);
        $username = Utils::request("username", -1);
        $password = Utils::request("userPassword", -1);
        $mail = Utils::request("userMail", -1);

        $this->checkChangeInUserInfos("myaccount", $username, $mail, $password);

        $userManager = new UserManager();
        $userManager->modifyUserInfos($userId, $mail, $username, $password);

        Utils::redirect("myaccount");
    }

    public function registerUserInfos(): void
    {
        //TODO que la modification ne correspond pas à un autre utilisateur (username ou email)
        $username = Utils::request("username", -1);
        $password = Utils::request("userPassword", -1);
        $mail = Utils::request("userMail", -1);

        $this->checkUserInfos("register", $username, $mail, $password);

        $userManager = new UserManager();
        $userId = $userManager->registerUser($username, $password, $mail);

        if ($userId) {
            $this->connectUser();
            Utils::redirect("myaccount");
        }
    }

}