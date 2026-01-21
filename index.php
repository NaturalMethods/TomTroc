<?php

require_once 'config/config.php';
require_once 'config/autoload.php';

$action = Utils::request('action', 'home');

try{

    switch($action){

        case 'home':
            $view = new View("Acceuil");
            $view->render("home",['title' => "Home"]);
            break;

        case 'books':
            $view = new View("books");
            $view->render("books",['title' => "Liste des livres"]);
            break;

        default:
            throw new Exception("La page demandée n'existe pas.");
    }



} catch(Exception $e){

    echo "raté";

}