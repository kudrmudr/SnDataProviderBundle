<?php

namespace kudrmudr\SnDataProviderBundle\Entity;

class Attachment
{
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_FILE = 'file';


    protected $ex_id;

    protected $file;

    protected $type;

    protected $message;

    public function setExId(string $ex_id)
    {
        $this->ex_id = $ex_id;
        return $this;
    }

    public function getExId(): string
    {
        return $this->ex_id;
    }

    public function setFile(string $file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}