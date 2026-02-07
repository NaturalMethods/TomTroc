<?php

class Chat extends AbstractEntity
{
    private User $senderUser;
    private array $messages;


    public function getMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(Message $message): void{

        $this->messages[] = $message;

    }

    public function setMessages(Array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getSenderUser() : User
    {
        return $this->senderUser;
    }

    /**
     * @param mixed $senderUser
     */
    public function setSenderUser(User $senderUser): void
    {
        $this->senderUser = $senderUser;
    }

    public function getLastMessage() : ?Message{
        if($this->messages)
        return $this->messages[count($this->messages) - 1];

        return null;
    }

}