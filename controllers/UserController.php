<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Class controller about user page and user
 */
class UserController
{
    /**
     * Check if user is not connected, if he is redirects to "myaccount" page
     * @return void
     */
    private function checkIfUserIsNotConnected(): void
    {
        if (isset($_SESSION['idUser']))
            Utils::redirect("myaccount");
    }

    /**
     * Check if username already exist and return a bool
     * @param string $username
     * @return bool
     */
    private function checkIfUsernameExists(string $username): bool
    {
        $userManager = new UserManager();
        $user = $userManager->getUserByUsername($username);

        if ($user)
            return true;
        return false;
    }

    /**
     * Check if email already exist
     * @param string $mail
     * @return bool
     */
    private function checkIfEmailExists(string $mail): bool
    {
        $userManager = new UserManager();
        $user = $userManager->getUserByMail($mail);

        if ($user)
            return true;
        return false;
    }

    /**
     * Check if Username or Email exist and redirect to $location with error message
     * @param string $location
     * @param string $username
     * @param string $mail
     * @param string $password
     * @return void
     */
    private function checkChangeInUserInfos(string $location, string $username, string $mail, string $password): void
    {
        if (!empty($username) and $this->checkIfUsernameExists($username))
            Utils::redirect($location, ['error' => 'usernameExists']);

        if (!empty($mail) and $this->checkIfEmailExists($mail))
            Utils::redirect($location, ['error' => 'emailExists']);

    }

    /**
     * Check user infos from form and redirect to location if invalid
     * @param string $location
     * @param string $username
     * @param string $mail
     * @param string $password
     * @return void
     */
    private function checkUserInfos(string $location, string $username, string $mail, string $password): void
    {
        if (empty($username) || empty($mail) || empty($password))
            Utils::redirect($location, ['error' => 'emptyFields']);

        if ($this->checkIfUsernameExists($username))
            Utils::redirect($location, ['error' => 'usernameExists']);

        if ($this->checkIfEmailExists($mail))
            Utils::redirect($location, ['error' => 'emailExists']);

    }

    /**
     * Return an array with only the right message not hidden which will
     * be injected in the template
     * @return string[]|null
     */
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

    /**
     * Check if password from the user is correct if not redirect with error message
     * @param User $user
     * @param string $password
     * @return void
     */
    private function checkPassword(User $user, string $password): void
    {
        if (!password_verify($password, $user->getPassword()))
            Utils::redirect("connect", ['error' => 'wrongIDs']);
    }

    /**
     * Connect a user by checking email and password or redirect with error message if invalid
     * @return void
     */
    #[NoReturn]
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $mail = htmlspecialchars(Utils::request("userMail"));
        $password = htmlspecialchars(Utils::request("userPassword"));

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByMail($mail);
        if (!$user) {
            Utils::redirect("connect", ['error' => 'invalidMail']);
        }

        //TODO regenerer sessID eviter la fixation.

        $this->checkPassword($user, $password);

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getIdUser();

        // On redirige vers la page d'administration.
        Utils::redirect("home");
    }

    /**
     * Disconnect user by destroying session (which is recreated auto in index.php)
     * @return void
     */
    #[NoReturn]
    public function disconnectUser(): void
    {
        session_unset();
        session_destroy();

        header('Location: index.php?action=home');
        exit;
    }

    /**
     * Function called to display the "myaccount" page if user is connected
     * @return void
     * @throws Exception
     */
    public function showMyAccount(): void
    {
        Utils::checkIfUserIsConnected();

        $id = $_SESSION['idUser'];

        $errorMessage = $this->setErrorMessage();

        $userManager = new UserManager();
        $user = $userManager->getUserByID($id);

        if (!$user)
            throw new Exception("L'utilisateur n'existe pas.");

        $bookManager = new BookManager();
        $books = $bookManager->getUserBooks($user->getIdUser());

        if (!$user->getUserPic() || !file_exists(USERS_IMAGES . $user->getUserPic()))
            $user->setUserPic("damiers.png");

        $memberAge = $user->memberDuration();

        $view = new View();
        $view->render("account", ['user' => $user, 'books' => $books, 'memberAge' => $memberAge, 'errorMessage' => $errorMessage], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);
    }

    /**
     * Function called to display a public account page by id
     * @return void
     * @throws Exception
     */
    public function showPublicAccount(): void
    {

        $id = htmlspecialchars(Utils::request("id"));

        $userManager = new UserManager();
        $user = $userManager->getUserByID($id);

        if (!$user)
            Utils::redirect("home");

        $bookManager = new BookManager();
        $books = $bookManager->getUserBooks($user->getIdUser());

        // TODO définir sur damiers dans UserManager ?
        if (!$user->getUserPic())
            $user->setUserPic("damiers.png");

        $memberAge = $user->memberDuration();

        $view = new View();
        $view->render("publicaccount", ['user' => $user, 'memberAge' => $memberAge, 'books' => $books], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);

    }

    /**
     * Function called to display the register page if not connected
     * @return void
     */
    public function showRegister(): void
    {
        $this->checkIfUserIsNotConnected();

        $errorMessage = $this->setErrorMessage();

        $view = new View();
        $view->render("register", ['errorMessage' => $errorMessage], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);
    }

    /**
     * Function called to display the connect page if not connected
     * @return void
     */
    public function showConnect(): void
    {
        $this->checkIfUserIsNotConnected();

        $errorMessage = 'hidden';
        $error = Utils::request('error');
        if ($error == "wrongIDs")
            $errorMessage = '';

        $view = new View();
        $view->render("connect", ['errorMessage' => $errorMessage], ['unreadMSG' => ChatController::getUnreadMessagesCount()]);
    }

    /**
     * Check and change infos of user from the form in "myaccount" page
     * @return void
     */
    #[NoReturn]
    public function changeUserInfos(): void
    {
        Utils::checkIfUserIsConnected();

        $userId = htmlspecialchars(Utils::request("id", -1));
        $username = htmlspecialchars(Utils::request("username", -1));
        $password = htmlspecialchars(Utils::request("userPassword", -1));
        $mail = htmlspecialchars(Utils::request("userMail", -1));

        $this->checkChangeInUserInfos("myaccount", $username, $mail, $password);

        $userManager = new UserManager();
        $userManager->modifyUserInfos($userId, $mail, $username, $password);

        Utils::redirect("myaccount");
    }

    /**
     * Function called to upload a user image from myaccount page
     * @return void
     */
    #[NoReturn]
    public function uploadUserPic(): void
    {
        Utils::checkIfUserIsConnected();

        $idUser = $_SESSION["idUser"];
        $location = "myaccount";

        $userManager = new UserManager();
        $user = $userManager->getUserByID($idUser);
        $userOldPic = $user->getUserPic();

        $name = Utils::savePicToDir($location, USERS_IMAGES);
        $userNewPic = USERS_IMAGES . $name;


        if (file_exists($userNewPic)) {
            if ($userManager->setUserPicById($idUser, $name)) {
                Utils::deleteOldPic(USERS_IMAGES . $userOldPic);
            } else {
                unlink($userNewPic);
                Utils::redirect($location, ['error' => 'uploadError']);
            }
        }
        Utils::redirect($location);
    }

    /**
     * Register a user with infos from the form in register page
     * @return void
     */
    public function registerUserInfos(): void
    {
        $username = htmlspecialchars(Utils::request("username", -1));
        $password = htmlspecialchars(Utils::request("userPassword", -1));
        $mail = htmlspecialchars(Utils::request("userMail", -1));

        $this->checkUserInfos("register", $username, $mail, $password);

        $userManager = new UserManager();
        $userId = $userManager->registerUser($username, $password, $mail);

        if ($userId) {
            $this->connectUser();
        }
    }

}