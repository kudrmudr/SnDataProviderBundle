<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use kudrmudr\SnDataProviderBundle\Entity\Language;
use kudrmudr\SnDataProviderBundle\Entity\User;

class Facebook extends AbstractProvider
{

    const API_HOST = 'https://graph.facebook.com/v2.11/';

    public function sendMessage(string $userId, string $text)
    {
        if ($text) {

            $response = $this->client->post(self::API_HOST . 'me/messages', [
                'form_params' => [
                    'recipient' => ['id' => $userId],
                    'message' => ['text' => $text]
                ],
                'query' => [
                    'access_token' => $this->accessToken
                ]
            ]);

            return $this->json($response);
        }
    }

    public function sendImages(string $userId, Array $images)
    {

         foreach ($images as $image) {

             $response = $this->client->post(self::API_HOST . 'me/messages', [
                 'multipart' => [
                     [
                         'name' => 'recipient',
                         'contents' => '{"id": "'.$userId.'"}',
                     ],
                     [
                         'name' => 'message',
                         'contents' => '{"attachment":{"type":"image", "payload":{"is_reusable":true}}}',
                     ],
                     [
                         'name' => 'filedata',
                         'contents' => fopen($image, 'r')
                     ]
                 ],
                 'query' => [
                     'access_token' => $this->accessToken
                 ]
             ]);
         }
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getUser(string $userId): ?User
    {
        $response = $this->client->get(self::API_HOST . $userId, [
            'query' => [
                'access_token' => $this->accessToken
            ]
        ]);

        if ($res = $this->json($response)) {

            $language = new Language();
            $language->setCode(stristr($res['locale'], '_', true));
            $language->setName('None');

            $user = new User();
            $user->setLanguage($language);
            $user->setProviderName(self::class);
            $user->setExId($res['id']);
            $user->setFirstName($res['first_name']);
            $user->setLastName($res['last_name']);
            $user->setImage($res['profile_pic']);

            return $user;
        }
        return null;
    }
}
