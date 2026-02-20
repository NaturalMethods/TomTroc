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
     * Get all conversations with their last message to send to client
     * @param int $idUser
     * @return array|null
     */
    public function getConversations(int $idUser): ?array
    {
        $sql = "SELECT
                        u.idUser AS userID,
                        u.username,
                        u.userPic,
                        m.message AS lastMessage,
                        m.sentAt
                FROM messages m
                JOIN users u
                ON (
                        (u.idUser = m.idSender AND m.idReceiver = :receiverId)
                        OR (u.idUser = m.idReceiver AND m.idSender = :receiverId)
                   )
                WHERE m.idMessage = (
                    SELECT MAX(m2.idMessage)
                    FROM messages m2
                    WHERE (
                            (m2.idSender = :receiverId AND m2.idReceiver = u.idUser)
                            OR (m2.idSender = u.idUser AND m2.idReceiver = :receiverId)
                          )
                    )
                ORDER BY m.idMessage DESC;";

        $result = $this->db->query($sql, ['receiverId' => $idUser]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Return all messages between a sender and a receiver
     * @param int $idUser
     * @param int $contactId
     * @return array|null
     */
    public function getMessages(int $idUser, int $contactId): ?array
    {

        $sql = "SELECT
                        m.*
                FROM messages m
                WHERE
                     (m.idSender = :idSender   AND m.idReceiver = :idReceiver)
                OR   (m.idSender = :idReceiver AND m.idReceiver = :idSender)
                ORDER BY m.sentAt;
                ";
        $result = $this->db->query($sql, ['idReceiver' => $idUser, 'idSender' => $contactId]);
        $messages = [];

        while ($message = $result->fetch()) {
            $messages[] = new Message($message);
        }
        return $messages;
    }

    /**
     * Return all messages with message id greater than the message id specified
     * @param int $idUser
     * @param int $contactId
     * @param int $idMessage
     * @return array|null
     */
    public function getUnreadMessages(int $idUser, int $contactId, int $idMessage): ?array
    {
        error_log("lastId envoyé = " . $idMessage);

        $sql = "SELECT
                        m.*
                FROM messages m
                WHERE
                     ((m.idSender = :idSender   AND m.idReceiver = :idReceiver)
                OR   (m.idSender = :idReceiver AND m.idReceiver = :idSender))
                AND m.idMessage > :idMessage
                ORDER BY m.sentAt DESC;
                ";
        $result = $this->db->query($sql, ['idReceiver' => $idUser, 'idSender' => $contactId, 'idMessage' => $idMessage]);
        $messages = [];

        while ($message = $result->fetch()) {
            $messages[] = new Message($message);
        }
        return $messages;

    }

    /**
     * Update the read mark of a message to set to 0
     * @param int $idSender
     * @param int $idReceiver
     * @return void
     */
    public function updateReadMark(int $idSender, int $idReceiver): void
    {

        $sql = "UPDATE messages set unread = 0 WHERE idSender = :idSender and idReceiver = :idReceiver ; ";
        $result = $this->db->query($sql, ['idSender' => $idSender, 'idReceiver' => $idReceiver]);
        $result->fetch();
    }

    /**
     * Add a message to the database
     * @param $message
     * @param $idSender
     * @param $idReceiver
     * @return void
     */
    public function addMessageToDB($message, $idSender, $idReceiver): void
    {

        $sql = "INSERT INTO messages (message,idSender,idReceiver) VALUES (:message, :idSender, :idReceiver);";
        $params = [
            'message' => $message,
            'idSender' => $idSender,
            'idReceiver' => $idReceiver,
        ];

        //TODO créé une fonction qui gere les retours SQL pour les select, insert, update...)
        $this->db->query($sql, $params);
    }

    /**
     * Return true if a chat exist between two users
     * @param $senderID
     * @param $receiverID
     * @return bool
     */
    public function checkIfChatExist($senderID, $receiverID): bool{

        $sql = "SELECT 1
                FROM messages
                WHERE 
                    (idSender = :user1 AND idReceiver = :user2)
                OR  (idSender = :user2 AND idReceiver = :user1)
                LIMIT 1;" ;

        $params = [
            'user1' => $senderID,
            'user2' => $receiverID
        ];
        $result = $this->db->query($sql, $params);

        return (bool)$result->fetch(PDO::FETCH_ASSOC);

    }

}