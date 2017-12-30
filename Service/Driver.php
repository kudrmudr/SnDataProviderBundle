<?php

namespace kudrmudr\SnDataProviderBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use kudrmudr\SnDataProviderBundle\Entity\Message;

class Driver
{
    protected $service_container;

    public function __construct(ContainerInterface $service_container)
    {
        $this->service_container = $service_container;
    }

    function send(Message $message)
    {
        $user = $message->getUser();

        $provider = $this->service_container->get($user->getProviderName());

        $provider->sendMessage($message);
    }
}