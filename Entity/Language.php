<?php

namespace kudrmudr\SnDataProviderBundle\Entity;

/**
 * Language
 */
class Language
{
    protected $code;

    protected $name;

    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}