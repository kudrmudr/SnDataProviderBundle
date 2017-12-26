<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use GuzzleHttp\Client;

class Vk extends AbstractProvider
{
    const API_HOST = 'https://api.vk.com/method/';

    protected $accessToken;
    protected $client;

    /**
     * Vk constructor.
     * @param string $accessToken
     */
    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;

        $this->client = new Client([
            'base_uri' => self::API_HOST,
        ]);
    }

    /**
     * @param string $userId
     * @param string $text
     * @return mixed
     */
    public function sendMessage(string $userId, string $text)
    {
        $response = $this->client->post('messages.send', [
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

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getUser(string $userId)
    {
        $response = $this->client->get('users.get', [
            'query' => [
                'user_ids' => $userId,
                'fields' => 'photo_400_orig,photo_200_orig',
            ]
        ]);

        if ($res = $this->json($response)) {
            if ($res1 = array_shift($res)) {
                return $res1[0];
            }
        }
    }

    public function sendImages(string $userId, Array $images)
    {
        $response = $this->client->get('photos.getMessagesUploadServer', [
            'query' => [
                'access_token' => $this->accessToken
            ]
        ]);

        if ($uploadserver = $this->json($response)) {

            $uploadserver = array_shift($uploadserver);

            $uploadserver_url = $uploadserver['upload_url'];

            foreach ($images as $image_path) {

                $client = new Client();

                if ($response = $client->request('POST', $uploadserver_url, [
                    'multipart' => [
                        [
                            'name' => 'photo',
                            'contents' => fopen($image_path, 'r'),
                        ],
                    ]
                ])
                ) {

                    $img_uploaded_result = $this->json($response);


                    $img_response = $this->client->post('photos.saveMessagesPhoto', [
                        'form_params' => $img_uploaded_result,
                        'query' => [
                            'access_token' => $this->accessToken
                        ]
                    ]);

                    if ($img_to_att = $this->json($img_response)) {

                        $this->client->post('messages.send', [
                            'form_params' => array(
                                'user_id' => $userId,
                                'attachment' => 'photo'.$img_to_att['response'][0]['owner_id'].'_'.$img_to_att['response'][0]['pid'],
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