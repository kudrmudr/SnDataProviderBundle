<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;


interface ProviderInterface
{
    public function sendMessage(Message $message);

    public function getUser(string $userId): ?User;
}