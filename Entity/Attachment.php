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

    /**
     * @param string $ex_id
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
     * @param string $file
     * @return $this
     */
    public function setFile(string $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFile(): ?string
    {
        return $this->file;
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
}