<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use kudrmudr\SnDataProviderBundle\Entity\Language;
use kudrmudr\SnDataProviderBundle\Entity\User;
use kudrmudr\SnDataProviderBundle\Entity\Message;

class Facebook extends AbstractProvider
{
    const API_HOST = 'https://graph.facebook.com/v2.11/';

    /**
     * @param string $userId
     * @return User|null
     */
    public function getUser(string $userId): ?User
    {
        $response = $this->client->get(self::API_HOST . $userId, [
            'query' => [
                'access_token' => $this->accessToken
            ]
        ]);

        if ($res = $this->json($response)) {

            $user = new User();

            if (isset($res['locale'])) {
                $language = new Language();
                $language->setCode(stristr($res['locale'], '_', true));
                $language->setName('None');
                $user->setLanguage($language);
            }

            $user->setProviderName(self::class);
            $user->setExId($res['id']);

            if (isset($res['name'])) {
                $nameAndLastname = explode(' ', $res['name']);
                $user->setFirstName($nameAndLastname[0]);
                $user->setLastName($nameAndLastname[1]);
            }
            if (isset($res['first_name'])) {
                $user->setFirstName($res['first_name']);
            }
            if (isset($res['last_name'])) {
                $user->setLastName($res['last_name']);
            }
            if (isset($res['profile_pic'])) {
                $user->setImage($res['profile_pic']);
            }

            return $user;
        }
        return null;
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function sendMessage(Message $message)
    {
        if ($message->getText()) {

            $this->client->post(self::API_HOST . 'me/messages', [
                'form_params' => [
                    'recipient' => ['id' => $message->getUser()->getExId()],
                    'message' => ['text' => $message->getText()]
                ],
                'query' => [
                    'access_token' => $this->accessToken
                ]
            ]);
        }

        foreach ($message->getAttachments() as $attachment) {

            $this->client->post(self::API_HOST . 'me/messages', [
                'multipart' => [
                    [
                        'name' => 'recipient',
                        'contents' => '{"id": "' . $message->getUser()->getExId() . '"}',
                    ],
                    [
                        'name' => 'message',
                        'contents' => '{                                 
                              "attachment":
                                    {
                                        "type":"' . $attachment->getType() . '",
                                        "payload": { "is_reusable":true }
                                    }
                              }',
                    ],
                    [
                        'name' => 'filedata',
                        'contents' => fopen($attachment->getFile(), 'r')
                    ]
                ],
                'query' => [
                    'access_token' => $this->accessToken
                ]
            ]);
        }
    }

    /**
     * @param Message $message
     */
    public function sendPost(Message $message)
    {
        if ($message->getParentId()) {
            $url = self::API_HOST . $message->getParentId() . '/comments';
        } else {
            $url = self::API_HOST . 'me/feed';
        }

        $fields = array();

        if ($message->getText()) {
            $fields[] = array(
                'name' => 'message',
                'contents' => $message->getText(),
            );
        }

        if ($attachments = $message->getAttachments()) {
            $fields[] = array(
                'name' => 'source',
                'contents' => fopen($attachments[0]->getFile(), 'r')
            );
        }

        if ($fields) {
            $this->client->post($url, [
                'multipart' => $fields,
                'query' => [
                    'access_token' => $this->accessToken
                ]
            ]);
        }
    }
}