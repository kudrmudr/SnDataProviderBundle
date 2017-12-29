<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use kudrmudr\SnDataProviderBundle\Entity\Language;
use kudrmudr\SnDataProviderBundle\Entity\User;

class Vk extends AbstractProvider
{
    const API_HOST = 'https://api.vk.com/method/';

    /**
     * @param string $userId
     * @param string $text
     * @return mixed
     */
    public function sendMessage(string $userId, string $text)
    {
        if ($text) {

            $response = $this->client->post(self::API_HOST . 'messages.send', [
                'form_params' => [
                    'user_id' => $userId,
                    'message' => $text
                ],
                'query' => [
                    'access_token' => $this->accessToken
                ]
            ]);

            return $this->json($response);
        }
    }

    /**
     * @param string $userId
     * @return User|null
     */
    public function getUser(string $userId): ?User
    {
        $response = $this->client->get(self::API_HOST . 'users.get', [
            'query' => [
                'user_ids' => $userId,
                'fields' => 'photo_max_orig',
            ]
        ]);

        if ($res = $this->json($response)) {
            if (isset($res['response'])) {
                $vkUser = $res['response'][0];

                $language = new Language();
                $language->setCode('ru');
                $language->setName('Русский');

                $user = new User();
                $user->setLanguage($language);
                $user->setProviderName(self::class);
                $user->setExId($vkUser['uid']);
                $user->setFirstName($vkUser['first_name']);
                $user->setLastName($vkUser['last_name']);
                $user->setImage($vkUser['photo_max_orig']);
                return $user;
            }
        }

        return null;
    }

    public function sendImages(string $userId, Array $images)
    {
        $response = $this->client->get(self::API_HOST . 'photos.getMessagesUploadServer', [
            'query' => [
                'access_token' => $this->accessToken
            ]
        ]);

        if ($uploadserver = $this->json($response)) {

            $uploadserver = array_shift($uploadserver);

            foreach ($images as $image_path) {

                if ($response = $this->client->request('POST', $uploadserver['upload_url'], [
                    'multipart' => [
                        [
                            'name' => 'photo',
                            'contents' => fopen($image_path, 'r'),
                        ],
                    ]
                ])
                ) {

                    $img_uploaded_result = $this->json($response);

                    $img_response = $this->client->post(self::API_HOST . 'photos.saveMessagesPhoto', [
                        'form_params' => $img_uploaded_result,
                        'query' => [
                            'access_token' => $this->accessToken
                        ]
                    ]);

                    if ($img_to_att = $this->json($img_response)) {

                        $this->client->post(self::API_HOST . 'messages.send', [
                            'form_params' => array(
                                'user_id' => $userId,
                                'attachment' => 'photo' . $img_to_att['response'][0]['owner_id'] . '_' . $img_to_att['response'][0]['pid'],
                            ),
                            'query' => [
                                'access_token' => $this->accessToken
                            ]
                        ]);
                    }
                }
            }
        }
    }
}