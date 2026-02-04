<?php

class ChatController
{

    public function showChat(): void
    {
        $view = new View();
        $view->render("chat");

    }

}