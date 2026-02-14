<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Class controller about Chat page and messages
 */
class ChatController
{
    /**
     * Return the unread message count of the connected user
     * @return int
     */
    public static function getUnreadMessagesCount(): int
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

        $view = new View();
        $view->render("chat", [], ['unreadMSG' => $this->getUnreadMessagesCount()]);

    }

    /**
     * Send a conversation list with their last messages of the user connected to the client
     * @return void
     */
    #[NoReturn]
    public function getConversations(): void
    {
        UserController::checkIfUserIsConnected();
        //TODO Réaliser un lastMessageID Global

        $chatManager = new ChatManager();
        $chats = $chatManager->getConversations($_SESSION['idUser']);

        echo json_encode($chats);
        exit;
    }

    /**
     * Send all messages between a contact and the user connected to the client
     * @return void
     */
    #[NoReturn]
    public function getMessages(): void
    {

        UserController::checkIfUserIsConnected();

        $contact = Utils::request('id');
        //TODO check ID du contact
        $chatManager = new ChatManager();
        $messages = $chatManager->getMessages($_SESSION['idUser'], $contact);

        $messagesArray = [];

        foreach ($messages as $message) {
            $messagesArray[] = $message->toArray();
        }

        echo json_encode($messagesArray);
        exit;
    }

    /**
     * Get a message from the client and add it to database
     * @return void
     */
    #[NoReturn]
    public function sendMessage(): void
    {
        UserController::checkIfUserIsConnected();
        header('Content-Type: application/json');

        $message = $_POST['message'] ?? '';
        $receiver = $_POST['receiver'] ?? '';

        if (!empty($message) && !empty($receiver)) {
            $chatManager = new ChatManager();
            $chatManager->addMessageToDB($message, $_SESSION['idUser'], $receiver);
            $response = ['status' => $receiver, 'message' => $message];

        } else
            $response = ['status' => 'error', 'message' => 'Filed missing'];

        echo json_encode($response);
        exit;
    }

    /**
     * Create a chat between 2 users
     * @return void
     */
    #[NoReturn]
    public function sendMessageToConUser(): void
    {
        UserController::checkIfUserIsConnected();

        $contactId = Utils::request('ownerID');

        $chatManager = new ChatManager();
        if ($chatManager->checkIfChatExist($contactId, $_SESSION['idUser']))
            Utils::redirect('chat');

        $message = "Envoyer un nouveau message";

        if (!empty($contactId)) {

            $chatManager->addMessageToDB($message, $contactId, $_SESSION['idUser']);
        }

        Utils::redirect('chat');
    }

    /**
     * Get a read mark from the client and update database
     * @return void
     */
    #[NoReturn]
    public function sendReadMark(): void
    {
        UserController::checkIfUserIsConnected();
        $senderID = $_POST['senderId'] ?? '';

        if (!empty($senderID)) {
            $chatManager = new ChatManager();
            $chatManager->updateReadMark((int)$senderID, $_SESSION['idUser']);
            $response = ['senderID' => $senderID];
        } else {
            $response = ['status' => 'error', 'message' => 'Field missing'];
        }

        echo json_encode($response);
        exit();

    }

    /**
     * Send all message above the idMessage specified in the request to the client
     * @return void
     */
    #[NoReturn]
    public function getNewMessages(): void
    {
        UserController::checkIfUserIsConnected();
        $contact = Utils::request('id');
        $idMessage = (int)Utils::request('idMsg');

        $chatManager = new ChatManager();
        $messages = $chatManager->getUnreadMessages($_SESSION['idUser'], $contact, $idMessage);

        $messagesArray = [];

        if ($messagesArray != null || $messagesArray > 0) {
            foreach ($messages as $message) {
                $messagesArray[] = $message->toArray();
            }

            echo json_encode($messagesArray);
            exit;
        } else
            echo json_encode([]);
        exit;

    }

}