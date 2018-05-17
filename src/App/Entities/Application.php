<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Id\UuidGenerator as Uuid;
use App\Database\Types\ApplicationType;
use App\Database\EntityInterface;
use App\Services\Slugify\SlugifyInterface as Slugify;

/**
 * @ORM\Entity
 * @ORM\Table(name="applications", uniqueConstraints={@UniqueConstraint(name="slug", columns={"slug"})})
 */
class Application implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $type;

    /**
     * @ManyToOne(targetEntity="App\Entities\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $owner;

    private static $setterMap = [
        'name'        => 'setName',
        'description' => 'setDescription',
        'url'         => 'setUrl',
        'type'        => 'setType',
        'owner'       => 'setAppOwner'
    ];

    public function getId(): string
    {
        return $this->id;
    }

    public static function getSetterMap()
    {
        return self::$setterMap;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSlug(string $slug): void
    {
        // Accept string without space and in lowercase
        if (!preg_match('/[\sA-Z]+/', $slug)) {
            $this->slug = $slug;

            return;
        }

        throw new \Exception("The string '$slug' is not an slug");
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setType(string $type): void
    {
        if (defined("\App\Database\Types\ApplicationType::{$type}")) {
            $this->type = $type;
            return;
        }

        throw new \Exception('Application type not found');
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setAppOwner(User $user)
    {
        $this->owner = $user;
    }

    public function getAppOwner(): User
    {
        return $this->owner;
    }

    public function fromArray(array $data): void
    {
        $this->setName($data['name']);
        $this->setSlug($data['slug']);
        $this->setDescription($data['description']);
        $this->setUrl($data['url']);
        $this->setType($data['type']);
        $this->setAppOwner($data['owner']);
    }

    public function toArray(): array
    {
        return [
            'name'        => $this->getName(),
            'description' => $this->getDescription(),
            'url'         => $this->getUrl(),
            'type'        => $this->getType(),
            'owner'       => $this->getAppOwner()
        ];
    }
}