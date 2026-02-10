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



        // On recupére tout les sender

        $view = new View();
        $view->render("chat", [], ['unreadMSG' => $this->getUnreadMessagesCount()]);

    }

    public function getSenderList(){

        $chatManager = new ChatManager();
        $senderList = $chatManager->getSenderList($_SESSION['idUser']);

        $chats = [];

        foreach ($senderList as $sender) {

            $chat = new Chat();
            $chat->setSenderUser($sender);

            $message = $chatManager->getLastMessage($_SESSION['idUser'], $sender->getIdUser());
            $chat->addMessage($message);

            $chats[]= [ 'username'      => $sender->getUsername(),
                        'userPic'       => $sender->getUserPic(),
                        'lastMessage'   => $chat->getLastMessage()->getMessage(),
                        'sentAt'        => $chat->getLastMessage()->getSentAt()->format('H:i')    ];
        }

        echo json_encode($chats);
        exit;
    }

}