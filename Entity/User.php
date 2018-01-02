<?php

namespace kudrmudr\SnDataProviderBundle\Entity;

/**
 * User
 */
class User
{
    protected $ex_id;

    protected $provider_name;

    protected $language;

    protected $first_name;

    protected $last_name;

    protected $login;

    protected $image;

    public function setExId(string $ex_id)
    {
        $this->ex_id = $ex_id;

        return $this;
    }

    public function getExId(): string
    {
        return $this->ex_id;
    }

    public function setProviderName(string $provider_nam)
    {
        $this->provider_name = $provider_nam;

        return $this;
    }

    public function getProviderName(): string
    {
        return $this->provider_name;
    }

    public function setLanguage(Language $language)
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @param $login
     * @return $this
     */
    public function setLogin(?string $login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }


    public function setFirstName(?string $firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * Set lastName.
     *
     * @param string|null $lastName
     *
     * @return User
     */
    public function setLastName(?string $lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage(string $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
}