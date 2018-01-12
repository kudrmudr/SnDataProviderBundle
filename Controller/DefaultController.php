<?php

namespace kudrmudr\SnDataProviderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use kudrmudr\SnDataProviderBundle\Provider\Vk;
use kudrmudr\SnDataProviderBundle\Provider\Telegram;

use kudrmudr\SnDataProviderBundle\Entity\Language;
use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;
use kudrmudr\SnDataProviderBundle\Entity\Attachment;

class DefaultController extends BaseController
{
    public function vkAction(Request $request)
    {
        if ($content = $request->getContent() AND $data = json_decode($content, true)) {

            if ($data['type'] == 'message_new') {

                $user = new User();
                $user->setExId($data['object']['user_id']);
                $user->setProviderName(Vk::class);

                $message = new Message();
                $message->setExId($data['object']['id']);
                $message->setType(Message::MSG_TYPE_PM);
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

                        if ($att['type'] == 'photo' || $att['type'] == 'sticker') {

                            $attachment->setType(Attachment::TYPE_IMAGE);

                            foreach ($att[$att['type']] as $att_key => $att_val) {

                                if (substr($att_key, 0, 5) == 'photo') {

                                    $attachment->setFile($att_val);
                                }
                            }

                        } elseif ($att['type'] == 'doc') {

                            $attachment->setType(Attachment::TYPE_FILE);
                            $attachment->setFile($att['doc']['url']);
                        } elseif ($att['type'] == 'doc') {

                            $attachment->setType(Attachment::TYPE_FILE);
                            $attachment->setFile($att['doc']['url']);
                        } else {
                            continue;
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
            $message->setExId($data['message']['message_id']);
            $message->setType(Message::MSG_TYPE_PM);
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

                if (substr($data['message']['document']['mime_type'], 0, 5) == 'image') {
                    $attachment->setType(Attachment::TYPE_IMAGE);
                }

                $message->addAttachment($attachment);
            }

            $this->messageEventDispatch($message);
        }
        return new Response('ok');
    }

    public function twitterAction(Request $request)
    {
        $this->container->get('logger')->error('twitter' . $request->getContent());

        return new Response('ok');
    }
}