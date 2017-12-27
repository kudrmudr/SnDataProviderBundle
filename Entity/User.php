<?php

namespace kudrmudr\SnDataProviderBundle\Entity;

use kudrmudr\SnDataProviderBundle\Provider\AbstractProvider;
/**
 * User
 */
class User
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var AbstractProvider
     */
    private $provider;

    /**
     * @var string|null
     */
    private $first_name;

    /**
     * @var string|null
     */
    private $last_name;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $image;

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
     * @param $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param AbstractProvider $provider
     * @return $this
     */
    public function setProvider(AbstractProvider $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return AbstractProvider
     */
    public function getProvider() : AbstractProvider
    {
        return $this->provider;
    }

    /**
     * Set firstName.
     *
     * @param string|null $firstName
     *
     * @return User
     */
    public function setFirstName($firstName = null)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string|null
     */
    public function getFirstName()
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
    public function setLastName($lastName = null)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string|null
     */
    public function getLastName()
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
    public function getImage() : string
    {
        return $this->image;
    }
}