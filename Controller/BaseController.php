<?php

namespace kudrmudr\SnDataProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use kudrmudr\SnDataProviderBundle\Entity\Message;
use kudrmudr\SnDataProviderBundle\Event\MessageEvent;

abstract class BaseController extends Controller
{
    protected function messageEventDispatch(Message $message)
    {
        $this->container->get('event_dispatcher')->dispatch(
            'sn_data_provider_message_event',
            new MessageEvent($message)
        );
    }
}