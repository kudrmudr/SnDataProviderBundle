<?php

namespace kudrmudr\SnDataProviderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use kudrmudr\SnDataProviderBundle\Provider\Facebook;

use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;
use kudrmudr\SnDataProviderBundle\Entity\Attachment;

class FacebookController extends BaseController
{
    public function indexAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            if (!empty($data['entry'][0]['messaging'])) {
                foreach ($data['entry'][0]['messaging'] as $fbMessage) {
                    if (isset($fbMessage['message'])) {
                        $this->privateAction($fbMessage);
                    }
                }
            }

            if (!empty($data['entry'][0]['changes'])) {
                foreach ($data['entry'][0]['changes'] as $fbMessage) {
                    if (isset($fbMessage['field']) && $fbMessage['field']=='feed' && isset($fbMessage['value'])) {
                        $this->feedAction($fbMessage['value']);
                    }
                }
            }
        }

        return new Response('ok');
    }

    protected function privateAction(array $snMessage)
    {
        $user = new User();
        $user->setExId($snMessage['sender']['id']);
        $user->setProviderName(Facebook::class);

        $message = new Message();
        $message->setType(Message::MSG_TYPE_PM);
        $message->setUser($user);

        $dateCreated = new \DateTime();
        if ($snMessage['timestamp']) {
            $dateCreated->setTimestamp($snMessage['timestamp'] / 1000);
        }
        $message->setCreated($dateCreated);

        if (isset($snMessage['message']['text'])) {
            $message->setText($snMessage['message']['text']);
        }

        if (isset($snMessage['message']['attachments'])) {
            foreach ($snMessage['message']['attachments'] as $att) {

                if ($att['payload']['url']) {

                    $attachment = new Attachment();
                    $attachment->setFile($att['payload']['url']);

                    if ($att['type'] == 'image') {
                        $attachment->setType(Attachment::TYPE_IMAGE);
                    } elseif ($att['type'] == 'file') {
                        $attachment->setType(Attachment::TYPE_FILE);
                    } elseif ($att['type'] == 'video') {
                        $attachment->setType(Attachment::TYPE_VIDEO);
                    } elseif ($att['type'] == 'audio') {
                        $attachment->setType(Attachment::TYPE_AUDIO);
                    }

                    $message->addAttachment($attachment);
                }
            }
        }

        $this->messageEventDispatch($message);
    }

    protected function feedAction(array $snMessage)
    {
        $user = new User();
        $user->setExId($snMessage['from']['id']);
        $user->setProviderName(Facebook::class);

        $message = new Message();
        $message->setUser($user);
        $message->setType(Message::MSG_TYPE_POST);
        $message->setExId($snMessage['post_id']);

        $dateCreated = new \DateTime();
        if ($snMessage['created_time']) {
            $dateCreated->setTimestamp($snMessage['created_time']);
        }
        $message->setCreated($dateCreated);

        if (isset($snMessage['message'])) {
            $message->setText($snMessage['message']);
        }

        $this->messageEventDispatch($message);
    }
}