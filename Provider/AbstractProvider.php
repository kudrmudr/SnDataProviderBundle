<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

abstract class AbstractProvider
{
    abstract public function sendMessage(string $userId, string $text);
    
    abstract public function getUser(string $userId);

    public function json($response) {
        $obj = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Guzzle JSON decode ERROR: ' . json_last_error());
        }

        return $obj;
    }
}