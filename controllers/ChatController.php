<?php

class ChatController
{

    public static function getUnreadMessagesCount(){

        $chatManager = new ChatManager();
        if(isset($_SESSION['idUser']))
            return $chatManager->getUnreadMessagesCount($_SESSION['idUser']);
        else return 0;
    }

    public function showChat(): void
    {

        UserController::checkIfUserIsConnected();

        $chatManager = new ChatManager();
        //$chats = $chatManager->getChatListForUser($_SESSION['idUser']);

        // On recupére tout les sender
        $senderList = $chatManager->getSenderList($_SESSION['idUser']);

        $chats=[];

        foreach ($senderList as $sender) {

            $chat = new Chat();
            $chat->setSenderUser($sender);

            $message = $chatManager->getLastMessage($_SESSION['idUser'], $sender->getIdUser());
            $chat->addMessage($message);

            $chats[] = $chat;

        }

        // Récupérer le chat ouvert si l'idSender spécifié

        $view = new View();
        $view->render("chat",['chats'=>$chats, 'idSender' => null],['unreadMSG' => $this->getUnreadMessagesCount()]);

    }

}