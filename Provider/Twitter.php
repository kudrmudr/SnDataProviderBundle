<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use kudrmudr\SnDataProviderBundle\Entity\Language;
use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;

class Twitter extends AbstractProvider
{
    const API_HOST = '';

    public function sendMessage(Message $message)
    {
        if ($text) {


        }
    }

    public function getUser(string $userId): ?User
    {


        return null;
    }

    public function sendImages(string $userId, Array $images)
    {

    }
}