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
     * @var User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text.
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }
}
