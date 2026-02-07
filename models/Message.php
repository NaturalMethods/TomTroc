<?php

class Message extends AbstractEntity
{

    private int $idMessage;
    private DateTime $sentAt;
    private String $message;
    private int $idSender;
    private int $idReceiver;
    private int $unread;

    public function getIdMessage(): int
    {
        return $this->idMessage;
    }

    public function setIdMessage(int $idMessage): void
    {
        $this->idMessage = $idMessage;
    }

    public function getSentAt(): DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(String $sentAt): void
    {
        $this->sentAt = new DateTime($sentAt);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getIdSender(): int
    {
        return $this->idSender;
    }

    public function setIdSender(int $idSender): void
    {
        $this->idSender = $idSender;
    }

    public function getIdReceiver(): int
    {
        return $this->idReceiver;
    }

    public function setIdReceiver(int $idReceiver): void
    {
        $this->idReceiver = $idReceiver;
    }

    public function getUnread(): int
    {
        return $this->unread;
    }

    public function setUnread(int $unread): void
    {
        $this->unread = $unread;
    }


}