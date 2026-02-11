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

            $chats[]= [ 'userID'        => $sender->getIdUser(),
                        'username'      => $sender->getUsername(),
                        'userPic'       => $sender->getUserPic(),
                        'lastMessage'   => $chat->getLastMessage()->getMessage(),
                        'sentAt'        => $chat->getLastMessage()->getSentAt()->format('H:i')    ];
        }

        echo json_encode($chats);
        exit;
    }

    public function getMessages(){

        $contact = Utils::request('id');

        $chatManager = new ChatManager();
        $messages = $chatManager->getMessages($_SESSION['idUser'], $contact);

        $messagesarray = [];

        foreach ($messages as $message) {
            $messagesarray[] = $message->toArray();
        }

        echo json_encode($messagesarray);
        exit;
    }

    public function sendMessage(){

        header('Content-Type: application/json');

        $message = $_POST['message'] ?? '';
        $receiver = $_POST['receiver'] ?? '';

        // Ici, tu ferais l'insertion en base de données

        if(!empty($message) && !empty($receiver)) {
            // TODO: insertion en base
            echo json_encode(['status' => $receiver, 'message' => $message]);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Champs manquants']);
            exit;
        }

    }

    public function sendReadMark(){

        $message = $_POST['message'] ?? '';
        $receiver = $_POST['receiver'] ?? '';

        $chatManager = new ChatManager();
        $chatManager->updateReadMark($receiver);

        // Ici, tu ferais l'insertion en base de données

        if(!empty($message) && !empty($receiver)) {
            // TODO: insertion en base
            echo json_encode(['status' => $receiver, 'message' => $message]);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Champs manquants']);
            exit;
        }

    }

}