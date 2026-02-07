<?php

/**
 *  This class describe interaction with the messages table of database
 */
class ChatManager extends AbstractEntityManager
{

    /**
     * Return a count of the messages with unread at 1
     * @param int $idReceiver
     * @return int
     */
    public function getUnreadMessagesCount(int $idReceiver): int
    {

        $sql = "SELECT COUNT(*) AS nbUnread
                FROM messages
                WHERE unread = 1 AND idReceiver = :idReceiver;";

        $result = $this->db->query($sql, ['idReceiver' => $idReceiver]);
        return $result->fetch()['nbUnread'];

    }

    /**
     * Return an array of user object with all the distinct sender from
     * messages send to a receiver
     * @param int $idUser
     * @return ?array
     */
    public function getSenderList(int $idUser): ?array
    {

        // On récupére les usernames des differentes expediteur
        $sql = "SELECT DISTINCT
                            u.idUser,
                            u.username,
                            u.mail,
                            u.role,
                            u.createdAt,
                            u.userPic
                FROM users u
                JOIN messages m ON m.idSender = u.idUser
                WHERE m.idReceiver = :receiverId
                ORDER BY u.idUser;";

        $result = $this->db->query($sql, ['receiverId' => $idUser]);
        $users = [];

        while ($user = $result->fetch()) {
            $users[] = new User($user);
        }
        return $users;

    }

    /**
     *
     * @param int $idUser
     * @return array
     *
     * public function getChatListForUser(int $idUser){
     *
     * // On récupére les usernames des differentes expediteur
     * $sql = "SELECT DISTINCT u.idUser   AS idSender,
                                * u.username AS senderUsername,
     * u.userPic AS senderPic
     * FROM messages m
     * JOIN users u ON u.idUser = m.idSender
     * WHERE m.idReceiver = :receiverId
     * ORDER BY u.username; ";
 *
     * $result = $this->db->query($sql, ['receiverId' => $idUser]);
     * $chats = [];
     *
     * while ($chatresult = $result->fetch()) {
     * $chat =  new Chat($chatresult);
     * $chats[] = $chat;
     *
     * $message = $this->getLastMessagesForUser($idUser, $chat->getIdSender());
     * $chat->addMessage($message);
     * }
     * return $chats;
     *
     * }
     */

    /**
     * Return a message object containing the last message send between 2 users
     * @param int $idReceiver
     * @param int $idSender
     * @return Message|null
     */
    public function getLastMessage(int $idReceiver, int $idSender): ?Message
    {

        // On récupére les usernames des differentes expediteur
        $sql = "SELECT
                        m.*
                FROM messages m
                WHERE
                     (m.idSender = :senderId   AND m.idReceiver = :receiverId)
                OR   (m.idSender = :receiverId AND m.idReceiver = :senderId)
                ORDER BY m.sentAt DESC
                LIMIT 1;
                ";

        $result = $this->db->query($sql, ['receiverId' => $idReceiver, 'senderId' => $idSender]);
        $message = $result->fetch();
        if ($message) {
            return new Message($message);
        }
        return null;


    }
}