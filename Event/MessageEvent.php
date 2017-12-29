<?php

namespace kudrmudr\SnDataProviderBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use kudrmudr\SnDataProviderBundle\Entity\Message;

class MessageEvent extends Event
{
    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}