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

    private function checkIfUserIsNotConnected(): void{

        if(isset($_SESSION['idUser'])){
            Utils::redirect("myaccount");
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
        //if (!password_verify($password, $user->getPassword())) {
          //  $hash = password_hash($password, PASSWORD_DEFAULT);
           // throw new Exception("Le mot de passe est incorrect : $hash");
        //}

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getIdUser();

        // On redirige vers la page d'administration.
        Utils::redirect("home");
    }

    public function disconnectUser(): void{

        session_unset();
        session_destroy();

        header('Location: index.php?action=home');
        exit;
    }

    public function showMyAccount(): void{

        $this->checkIfUserIsConnected();

        $id = $_SESSION['idUser'];

        $userManager = new UserManager();
        $user = $userManager->getUserByID($id);


        $bookManager = new BookManager();
        $books = $bookManager->getUserBooks($user->getIdUser());

        if(!$user)
            throw new Exception("L'utilisateur n'existe pas.");

        if(!$user->getUserPic())
            $user->setUserPic("damiers.png");

        $view = new View();
        $view->render("account", ['user' => $user, 'books' => $books]);
    }

    public function showRegister(): void{

        $this->checkIfUserIsNotConnected();

        $error = Utils::request("error", "");
        $emptyFieldMessage = "hidden";

        if($error == "emptyFields") {
            $emptyFieldMessage = "";
        }
        $view = new View();
        $view->render("register",['emptyFieldMessage' => $emptyFieldMessage]);

    }

    public function showConnect(): void{

        $this->checkIfUserIsNotConnected();

        $view = new View();
        $view->render("connect", ['title' => "Connexion"]);
    }

    public function changeUserInfos(): void{

        //TODO Vérifier si l'utilisateur est connecté avec le même userID que celui qu'il tente de modifier
        //TODO que la modification ne correspond pas à un autre utilisateur (username ou email)
        $userId = Utils::request("id", -1);
        $username = Utils::request("username", -1);
        $password = Utils::request("userPassword", -1);
        $mail = Utils::request("userMail", -1);

        $userManager = new UserManager();
        $userManager->modifyUserInfos($userId,$mail,$username,$password);

        Utils::redirect("account",['id'=>$userId]);
    }

    public function registerUserInfos(): void{

        //TODO que la modification ne correspond pas à un autre utilisateur (username ou email)
        $username = Utils::request("username", -1);
        $password = Utils::request("userPassword", -1);
        $mail = Utils::request("userMail", -1);

        $userManager = new UserManager();
        $userId = $userManager->registerUser($username,$password,$mail);

        if($userId) {
            $this->connectUser();
            Utils::redirect("account", ['id' => $userId]);
        }
    }

}