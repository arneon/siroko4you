<?php

namespace Arneon\LaravelUsers\Domain\Entities;

use Illuminate\Support\Facades\Hash;

class User
{
    private $name;
    private $email;
    private $password;

    /**
     * @return mixed
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return User
     */
    public function setName(string $name) : User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail(string $email) : User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password) : User
    {
        $this->password = Hash::make($password);
        return $this;
    }
}
