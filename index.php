<?php

require_once 'config/config.php';
require_once 'config/autoload.php';

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
            $view = new View();
            $view->render("register", ['title' => "Inscription"]);
            break;

        case 'connect':
            $view = new View();
            $view->render("connect", ['title' => "Connexion"]);
            break;

        case 'account':
            $view = new View();
            $view->render("account", ['title' => "Mon compte"]);
            break;

        default:
            throw new Exception("La page demandée n'existe pas.");
    }


} catch (Exception $e) {

    echo "raté";

}