<?php

use JetBrains\PhpStorm\NoReturn;

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

    private function checkUploadedPic(string $location): void
    {
        $this->checkIfPicUploadedRight($location);
        $this->checkPicSize($location);
    }

    private function checkIfPicUploadedRight(string $location): void
    {

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            Utils::redirect($location, ['error' => 'picNotUploaded']);
        }

    }

    private function checkPicSize(string $location): void
    {

        $maxSize = 8 * 1024 * 1024; // 8 Mo

        if ($_FILES['image']['size'] > $maxSize) {
            Utils::redirect($location, ['error' => 'picTooBig']);
        }

    }

    private function checkPicType(string $location): ?string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);

        $allowed = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($mime, $allowed, true)) {
            Utils::redirect($location, ['error' => 'invalidFile']);
        }

        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => null
        };

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

    private function checkPassword(User $user, string $password): void
    {

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

        //TODO regenerer sessID eviter la fixation.

        $this->checkPassword($user, $password);

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getIdUser();

        // On redirige vers la page d'administration.
        Utils::redirect("home");
    }

    #[NoReturn]
    public function disconnectUser(): void
    {
        session_unset();
        session_destroy();

        header('Location: index.php?action=home');
        exit;
    }

    function memberDuration(string $createdAt): string
    {
        $created = new DateTime($createdAt);
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



    public function showMyAccount(): void
    {
        $this->checkIfUserIsConnected();

        $id = $_SESSION['idUser'];

        $errorMessage = $this->setErrorMessage();

        $userManager = new UserManager();
        $user = $userManager->getUserByID($id);


        $bookManager = new BookManager();
        $books = $bookManager->getUserBooks($user->getIdUser());

        //TODO faire le bouton éditer et supprimer de la liste des livres
        // TODO Voir si vraiment utile
        if (!$user)
            throw new Exception("L'utilisateur n'existe pas.");

        if (!$user->getUserPic() || !file_exists('./img/users_images/' . $user->getUserPic()))
            $user->setUserPic("damiers.png");

        $memberAge = $this->memberDuration($user->getCreatedAt());

        $view = new View();
        $view->render("account", ['user' => $user, 'books' => $books, 'memberAge' => $memberAge ,'errorMessage' => $errorMessage]);
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

        $memberAge = $this->memberDuration($user->getCreatedAt());

        $view = new View();
        $view->render("publicaccount", ['user' => $user, 'memberAge' => $memberAge ,'books' => $books]);

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
        if ($error == "wrongIDs")
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

    public function uploadUserPic(): void
    {
        $this->checkIfUserIsConnected();

        $this->checkUploadedPic("myaccount");
        $extension = $this->checkPicType("myaccount");

        $idUser = $_SESSION["idUser"];
        $userManager = new UserManager();
        $user = $userManager->getUserByID($idUser);
        $userOldPic = $user->getUserPic();

        $tmp = $_FILES['image']['tmp_name'];
        $name = uniqid() . "." . $extension;
        $userNewPic = './img/users_images/' . $name;


        move_uploaded_file($tmp, $userNewPic);

        if (file_exists($userNewPic)) {
            if ($userManager->setUserPicById($idUser, $name)) {
                if (!empty($userOldPic) && file_exists('./img/users_images/' . $userOldPic)) {
                    unlink('./img/users_images/' . $userOldPic);
                }
            } else {
                unlink($userNewPic);
                Utils::redirect("myaccount", ['error' => 'uploadError']);
            }
        }
        Utils::redirect("myaccount");
    }

    public function registerUserInfos(): void
    {
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