<?php

/**
 * Class controller about Chat page and messages
 */
class ChatController
{
    /**
     * Return the unread message count of the connected user
     * @return int
     */
    public static function getUnreadMessagesCount()
    {
        $chatManager = new ChatManager();
        if (isset($_SESSION['idUser']))
            return $chatManager->getUnreadMessagesCount($_SESSION['idUser']);
        else return 0;
    }

    /**
     * Function called to display "chat" page
     * @return void
     */
    public function showChat(): void
    {

        UserController::checkIfUserIsConnected();

        $chatManager = new ChatManager();
        //$chats = $chatManager->getChatListForUser($_SESSION['idUser']);

        // On recupére tout les sender
        $senderList = $chatManager->getSenderList($_SESSION['idUser']);

        $chats = [];

        foreach ($senderList as $sender) {

            $chat = new Chat();
            $chat->setSenderUser($sender);

            $message = $chatManager->getLastMessage($_SESSION['idUser'], $sender->getIdUser());
            $chat->addMessage($message);

            $chats[] = $chat;

        }

        // Récupérer le chat ouvert si l'idSender spécifié

        $view = new View();
        $view->render("chat", ['chats' => $chats, 'idSender' => null], ['unreadMSG' => $this->getUnreadMessagesCount()]);

    }

}