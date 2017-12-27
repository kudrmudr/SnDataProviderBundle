<?php

namespace kudrmudr\SnDataProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use kudrmudr\SnDataProviderBundle\Provider\AbstractProvider;
use kudrmudr\SnDataProviderBundle\Provider\Vk;
use kudrmudr\SnDataProviderBundle\Provider\Telegram;
use kudrmudr\SnDataProviderBundle\Provider\Facebook;

use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;

use kudrmudr\SnDataProviderBundle\Event\MessageWebhookEvent;

class DefaultController extends Controller
{
    public function indexAction()
    {
        echo 11122;
    }

    public function vkAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            if ($data['type'] == 'message_new') {

                $user = new User();
                $user->setId($data['object']['user_id']);
                $user->setProvider($this->get(Vk::class));

                $message = new Message();
                $message->setUser($user);
                $message->setText($data['object']['body']);

                $this->container->get('event_dispatcher')->dispatch(
                    'sn_data_provider_message_webhook_event',
                    new MessageWebhookEvent($message)
                );
            }
        }

        return new Response('ok');
    }

    public function telegramAction(Request $request)
    {


    }

    public function facebookAction(Request $request)
    {


    }
}
