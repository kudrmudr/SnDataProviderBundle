<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use GuzzleHttp\Client;
use kudrmudr\SnDataProviderBundle\Entity\User;

class Telegram extends AbstractProvider
{
    const API_HOST = 'https://api.telegram.org/bot';

    protected $accessToken;
    protected $client;

    /**
     * Telegram constructor.
     */
    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;

        $this->client = new Client([
            'base_uri' => self::API_HOST . $this->accessToken . '/',
        ]);
    }

    /**
     * @param $userId
     * @param $data
     * @param array $controls
     * @return mixed
     * @throws \Exception
     */
    public function sendMessage(string $userId, string $text)
    {
        if ($text) {

            $response = $this->client->post('sendMessage', [
                'json' => [
                    'chat_id' => $userId,
                    'text' => $text
                ]
            ]);

            return $this->json($response);
        }
    }

    public function sendImages(string $userId, Array $images)
    {
        foreach ($images as $image) {

            $this->client->post('sendPhoto', [
                'multipart' => [
                    [
                        'name'     => 'chat_id',
                        'contents' => $userId
                    ],
                    [
                        'name'     => 'photo',
                        'contents' => fopen($image, 'r')
                    ]
                ]
            ]);
        }
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getUser(string $userId) : ?User
    {
        $response = $this->client->get('getUserProfilePhotos', [
            'query' => [
                'user_id' => $userId
            ]
        ]);

        if ($response = $this->json($response)) {


            $user = new User();
            $user->setProvider($this);
            $user->setId($userId);

            if (isset($response['result']['photos'][0][0])) {

                $user->setImage(
                    $this->getFile($response['result']['photos'][0][1]['file_id'])
                );

            }

            return $user;
        }

        return null;
    }


    public function getFile(string $file_id)
    {
        $response = $this->client->get('getFile', [
            'query' => [
                'file_id' => $file_id
            ]
        ]);

        $response = $this->json($response);

        return 'https://api.telegram.org/file/bot'.$this->accessToken.'/'.$response['result']['file_path'];
    }

    /**
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public function setWebhook($url)
    {
        $response = $this->client->post('setWebhook', [
            'json' => [
                'url' => $url
            ]
        ]);

        return $this->json($response);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getWebhook()
    {
        $response = $this->client->get('getWebhookInfo');

        return $this->json($response);
    }
}