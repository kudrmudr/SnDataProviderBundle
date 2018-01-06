<?php

namespace kudrmudr\SnDataProviderBundle\Controller;

use AppBundle\Entity\Phrase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use kudrmudr\SnDataProviderBundle\Provider\Vk;
use kudrmudr\SnDataProviderBundle\Provider\Telegram;
use kudrmudr\SnDataProviderBundle\Provider\Facebook;

use kudrmudr\SnDataProviderBundle\Entity\Language;
use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;
use kudrmudr\SnDataProviderBundle\Entity\Attachment;

use kudrmudr\SnDataProviderBundle\Event\MessageEvent;

class DefaultController extends Controller
{
    protected function messageEventDispatch(Message $message)
    {
        $this->container->get('event_dispatcher')->dispatch(
            'sn_data_provider_message_event',
            new MessageEvent($message)
        );
    }

    public function vkAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            if ($data['type'] == 'message_new') {

                $user = new User();
                $user->setExId($data['object']['user_id']);
                $user->setProviderName(Vk::class);

                $message = new Message();
                $message->setUser($user);

                $dateCreated = new \DateTime();
                if ($data['object']['date']) {
                    $dateCreated->setTimestamp($data['object']['date']);
                }
                $message->setCreated($dateCreated);

                if (isset($data['object']['body'])) {
                    $message->setText($data['object']['body']);
                }

                if (isset($data['object']['attachments'])) {

                    foreach ($data['object']['attachments'] as $att) {

                        $attachment = new Attachment();
                        $attachment->setExId($att[$att['type']]['id']);

                        if ($att['type'] == 'photo') {

                            $attachment->setType(Attachment::TYPE_IMAGE);

                            foreach ($att['photo'] as $att_key=>$att_val) {

                                if (substr($att_key,0,5) == 'photo') {

                                    $attachment->setFile($att_val);
                                }
                            }

                        } elseif ($att['type'] == 'doc') {

                            $attachment->setType(Attachment::TYPE_FILE);
                            $attachment->setFile($att['doc']['url']);
                        }

                        $message->addAttachment($attachment);
                    }
                }

                if (isset($data['object']['geo'])) {
                    $message->setCoordinates(str_replace(' ', ', ', $data['object']['geo']['coordinates']));
                }

                $this->messageEventDispatch($message);
            }
        }

        return new Response('ok');
    }

    public function telegramAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            $language = new Language();
            $language->setCode(stristr($data['message']['from']['language_code'], '-', true));

            $user = new User();
            $user->setLanguage($language);
            $user->setExId($data['message']['from']['id']);
            $user->setProviderName(Telegram::class);
            $user->setFirstName($data['message']['from']['first_name']);
            $user->setLastName($data['message']['from']['last_name']);
            $user->setLogin($data['message']['from']['username']);

            $message = new Message();
            $message->setUser($user);

            $dateCreated = new \DateTime();
            if ($data['message']['date']) {
                $dateCreated->setTimestamp($data['message']['date']);
            }
            $message->setCreated($dateCreated);

            if (isset($data['message']['text'])) {
                $message->setText($data['message']['text']);
            }

            if (isset($data['message']['photo'])) {

                $attachment = new Attachment();

                $file = end($data['message']['photo']);
                $attachment->setFile($this->get(Telegram::class)->getFile($file['file_id']));
                $attachment->setType(Attachment::TYPE_IMAGE);
                $message->addAttachment($attachment);
            }

            if (isset($data['message']['photo'])) {

                $attachment = new Attachment();

                $file = end($data['message']['photo']);
                $attachment->setFile($this->get(Telegram::class)->getFile($file['file_id']));
                $attachment->setType(Attachment::TYPE_IMAGE);
                $message->addAttachment($attachment);
            }

            if (isset($data['message']['document'])) {

                $attachment = new Attachment();

                $attachment->setFile($this->get(Telegram::class)->getFile($data['message']['document']['file_id']));
                $attachment->setType(Attachment::TYPE_FILE);

                if (substr($data['message']['document']['mime_type'],0,5) == 'image') {
                    $attachment->setType(Attachment::TYPE_IMAGE);
                }

                $message->addAttachment($attachment);
            }

            $this->messageEventDispatch($message);
        }
        return new Response('ok');
    }

    public function facebookAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            if (!empty($data['entry'][0]['messaging'])) {

                foreach ($data['entry'][0]['messaging'] as $fbMessage) {

                    if (isset($fbMessage['message'])) {

                        $user = new User();
                        $user->setExId($fbMessage['sender']['id']);
                        $user->setProviderName(Facebook::class);

                        $message = new Message();
                        $message->setUser($user);

                        $dateCreated = new \DateTime();
                        if ($fbMessage['timestamp']) {
                            $dateCreated->setTimestamp($fbMessage['timestamp']/1000);
                        }
                        $message->setCreated($dateCreated);

                        if (isset($fbMessage['message']['text'])) {
                            $message->setText($fbMessage['message']['text']);
                        }

                        if (isset($fbMessage['message']['attachments']))
                        {
                            foreach ($fbMessage['message']['attachments'] as $att) {

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
                }
            }
        }

        return new Response('ok');
    }

    public function twitterAction(Request $request)
    {
        $this->container->get('logger')->error('twitter'.$request->getContent());

        return new Response('ok');
    }

}
