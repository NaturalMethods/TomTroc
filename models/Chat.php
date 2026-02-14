<?php

/**
 *  This class describe a chat entity
 */
class Chat extends AbstractEntity
{
    private User $senderUser;
    private array $messages;

    /**
     * Return an array with all the messages of the chat
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Add a message to the array of messages
     * @param Message $message
     * @return void
     */
    public function addMessage(Message $message): void
    {

        $this->messages[] = $message;

    }

    /**
     * Set the array messages
     * @param array $messages
     * @return void
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * Return the sender user object
     * @return mixed
     */
    public function getSenderUser(): User
    {
        return $this->senderUser;
    }

    /**
     * Set the send user
     * @param mixed $senderUser
     */
    public function setSenderUser(User $senderUser): void
    {
        $this->senderUser = $senderUser;
    }

    /**
     * Return the last message in the array messages
     * @return Message|null
     */
    public function getLastMessage(): ?Message
    {
        if ($this->messages)
            return $this->messages[count($this->messages) - 1];

        return null;
    }

}