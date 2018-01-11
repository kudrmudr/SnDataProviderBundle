<?php

namespace kudrmudr\SnDataProviderBundle\Entity;

/**
 * Message
 */
class Message
{
    const MSG_TYPE_PM = 'pm';

    const MSG_TYPE_POST = 'post';

    protected $ex_id;

    protected $type;

    protected $parent_id;

    protected $text;

    protected $coordinates;

    protected $created;

    protected $attachments = array();

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
    public function getText(): ?string
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
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $parent_id
     * @return $this
     */
    public function setParentId(string $parent_id)
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentId(): ?string
    {
        return $this->parent_id;
    }

    /**
     * @param string $coordinates
     * @return $this
     */
    public function setCoordinates(string $coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordinates(): string
    {
        return $this->coordinates;
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
    public function getUser(): User
    {
        return $this->user;
    }

    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

}