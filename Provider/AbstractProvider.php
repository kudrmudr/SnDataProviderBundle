<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use GuzzleHttp\Client;

abstract class AbstractProvider implements ProviderInterface
{
    protected $accessToken;
    protected $client;

    public function __construct(string $accessToken, Client $client)
    {
        $this->accessToken = $accessToken;
        $this->client = $client;
    }

    public function json($response)
    {
        $obj = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Guzzle JSON decode ERROR: ' . json_last_error());
        }

        return $obj;
    }
}