<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Id\UuidGenerator as Uuid;
use App\Database\EntityInterface;
use App\Database\Types\UserType;
use App\Exceptions\WrongEnumTypeException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users", uniqueConstraints={@UniqueConstraint(name="username", columns={"username"})})
 */
class User implements EntityInterface
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
     * @ORM\Column(type="string", length=2)
     */
    private $type = 'W';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * The applications that the user can interact
     * @ORM\ManyToMany(targetEntity="App\Entities\Application", mappedBy="team")
     */
    private $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    public function getId(): string
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

    public function setType(string $type): void
    {
        if (defined("\App\Database\Types\UserType::$type")) {
            $this->type = $type;
            return;
        }

        throw new WrongEnumTypeException($type, UserType::getTypes());
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isPartner(): bool
    {
        return $this->type === 'P';
    }

    public function isMaster(): bool
    {
        return $this->type === 'M';
    }

    public function isWritter(): bool
    {
        return $this->type === 'W';
    }

    public function addAplication(Application $app)
    {
        $this->applications[] = $app;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->getUsername(),
            'name'     => $this->getName()
        ];
    }

    public function fromArray(array $data): void
    {
        $this->setUsername($data['username']);
        $this->setName($data['name']);
        $this->setPassword($data['password']);
    }
}