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
    protected $ex_id;

    /**
     * @var string
     */
    protected $text;

    protected $created;

    /**
     * @var array
     */
    protected $images = array();

    /**
     * @var User
     */
    protected $user;

    /**
     * @param string $id
     * @return $this
     */
    public function setExId(string $ex_id)
    {
        $this->ex_id = $ex_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getExId(): string
    {
        return $this->ex_id;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setText(string $text)
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
     * @param \DateTime $created
     * @return $this
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
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