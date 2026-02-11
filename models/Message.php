<?php

/**
 *  This class describe a message entity
 */
class Message extends AbstractEntity
{

    private int $idMessage;
    private DateTime $sentAt;
    private string $message;
    private int $idSender;
    private int $idReceiver;
    private int $unread;

    /**
     * Return id of the message
     * @return int
     */
    public function getIdMessage(): int
    {
        return $this->idMessage;
    }

    /**
     * Set id of the message
     * @param int $idMessage
     * @return void
     */
    public function setIdMessage(int $idMessage): void
    {
        $this->idMessage = $idMessage;
    }

    /**
     * Return the send date of the message
     * @return DateTime
     */
    public function getSentAt(): DateTime
    {
        return $this->sentAt;
    }

    /**
     * Set the send date of the message
     * @param String $sentAt
     * @return void
     * @throws Exception
     */
    public function setSentAt(string $sentAt): void
    {
        $this->sentAt = new DateTime($sentAt);
    }

    /**
     * Return the content of the message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the content of the message
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Return the id of the sender
     * @return int
     */
    public function getIdSender(): int
    {
        return $this->idSender;
    }

    /**
     * Set the id of the sender
     * @param int $idSender
     * @return void
     */
    public function setIdSender(int $idSender): void
    {
        $this->idSender = $idSender;
    }

    /**
     * Return the id of the receiver
     * @return int
     */
    public function getIdReceiver(): int
    {
        return $this->idReceiver;
    }

    /**
     * Set the id of the receiver
     * @param int $idReceiver
     * @return void
     */
    public function setIdReceiver(int $idReceiver): void
    {
        $this->idReceiver = $idReceiver;
    }

    /**
     * Return the unread state of the message
     * @return int
     */
    public function getUnread(): int
    {
        return $this->unread;
    }

    /**
     * Set the unread state of the message
     * @param int $unread
     * @return void
     */
    public function setUnread(int $unread): void
    {
        $this->unread = $unread;
    }

    public function toArray(): array{

        return [
            "idMessage" => $this->idMessage,
            "sentAt" => $this->sentAt->format("d.m H:i"),
            "message" => $this->message,
            "idSender" => $this->idSender,
            "idReceiver" => $this->idReceiver,
            "unread" => $this->unread,
        ];

    }

}