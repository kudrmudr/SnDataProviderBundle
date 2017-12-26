<?php

namespace kudrmudr\SnDataProviderBundle\Provider;

use pimax\FbBotApp;
use pimax\UserProfile;
use pimax\Messages\Message AS FBMessage;
use pimax\Messages\ImageMessage;

class Facebook extends AbstractProvider
{

    protected $accessToken;
    protected $client;

    /**
     * Telegram constructor.
     */
    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->client = new FbBotApp($accessToken);

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
        $this->client->send(new FBMessage($userId, $text));
    }

    public function sendImages(string $userId, Array $images)
    {
        foreach ($images as $image) {
            $res = $this->client->send(new ImageMessage($userId, $image));

            print_R($res);
        }

    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getUser(string $userId) : UserProfile
    {
        return $this->client->userProfile($userId);
    }
}