<?php

namespace kudrmudr\SnDataProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use kudrmudr\SnDataProviderBundle\Provider\Vk;
use kudrmudr\SnDataProviderBundle\Provider\Telegram;
use kudrmudr\SnDataProviderBundle\Provider\Facebook;

use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;

use kudrmudr\SnDataProviderBundle\Event\MessageEvent;

class DefaultController extends Controller
{
    public function vkAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            if ($data['type'] == 'message_new') {

                $user = new User();
                $user->setExId($data['object']['user_id']);
                $user->setProviderName(Vk::class);

                $message = new Message();
                $message->setUser($user);
                $message->setText($data['object']['body']);

                $this->container->get('event_dispatcher')->dispatch(
                    'sn_data_provider_message_event',
                    new MessageEvent($message)
                );
            }
        }

        return new Response('ok');
    }

    public function telegramAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            $user = new User();
            $user->setExId($data['message']['from']['id']);
            $user->setProviderName(Telegram::class);
            $user->setFirstName($data['message']['from']['first_name']);
            $user->setLastName($data['message']['from']['last_name']);

            $message = new Message();
            $message->setUser($user);
            $message->setText($data['message']['text']);

            $this->container->get('event_dispatcher')->dispatch(
                'sn_data_provider_message_event',
                new MessageEvent($message)
            );
        }
        return new Response('ok');
    }

    public function facebookAction(Request $request)
    {
        return new Response('ok');
    }
}
