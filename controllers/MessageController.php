<?php
namespace controllers;
use models\Message;
class MessageController
{
    private $messageModel;
    public function __construct(Message $message)
    {
        $this->messageModel = $message;
    }
    
}