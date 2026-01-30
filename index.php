<?php

require_once 'config/config.php';
require_once 'config/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = Utils::request('action', 'home');

try {

    switch ($action) {

        case 'home':
            $bookController = new BookController();
            $bookController->showHome();
            break;

        case 'books':
            $bookController = new BookController();
            $bookController->showBooks();
            break;

        case 'detailbook':
            $bookController = new BookController();
            $bookController->showDetailBook();
            break;

        case 'register':
            $userController = new UserController();
            $userController->showRegister();
            break;

        case 'connect':
            $userController = new UserController();
            $userController->showConnect();
            break;

        case 'myaccount':
            $userController = new UserController();
            $userController->showMyAccount();
            break;

        case 'account':
            $userController = new UserController();
            $userController->showPublicAccount();
            break;

        case 'connectUser':
            $userController = new UserController();
            $userController->connectUser();
            break;

        case 'disconnect':
            $userController = new UserController();
            $userController->disconnectUser();
            break;

        case 'registerUserInfos':
            $userController = new UserController();
            $userController->registerUserInfos();
            break;

        case 'changeUserInfos':
            $userController = new UserController();
            $userController->changeUserInfos();
            break;

        default:
            throw new Exception("La page demandée n'existe pas.");
    }


} catch (Exception $e) {

    echo "raté";
    echo $e->getMessage();
}