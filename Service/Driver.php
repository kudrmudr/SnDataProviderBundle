<?php

namespace kudrmudr\SnDataProviderBundle\Service;

use kudrmudr\SnDataProviderBundle\Entity\Message;

class Driver
{

    function send(Message $message)
    {
        $user = $message->getUser();

        $provider = $user->getProvider();

        $provider->sendMessage($user->getId(), $message->getText());

        $provider->sendImages($user->getId(), $message->getImages());

    }
}