<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use kudrmudr\SnDataProviderBundle\Entity\User;

interface ProviderInterface
{
    public function sendMessage(string $userId, string $text);

    public function sendImages(string $userId, array $images);

    public function getUser(string $userId): ?User;
}