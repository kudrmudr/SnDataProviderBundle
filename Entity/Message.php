<?php

namespace kudrmudr\SnDataProviderBundle\Entity;

/**
 * Message
 */
class Message
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $images = array();

    /**
     * @var User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function addImage(string $image)
    {
        $this->images[] = $image;
        return $this;
    }

    /**
     * @return array
     */
    public function getImages() : array
    {
        return $this->images;
    }
}
