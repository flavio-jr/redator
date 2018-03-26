<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Id\UuidGenerator as Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="users", uniqueConstraints={@UniqueConstraint(name="username", columns={"username"})})
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    public function getId()
    {
        return $this->id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function toArray()
    {
        return [
            'username' => $this->getUsername(),
            'name'     => $this->getName(),
            'password' => $this->getPassword()
        ];
    }

    public function fromArray(array $data)
    {
        $this->setUsername($data['username']);
        $this->setName($data['name']);
        $this->setPassword($data['password']);
    }
}