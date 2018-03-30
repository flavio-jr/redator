<?php

namespace App\Entities;

use App\Database\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Id\UuidGenerator as Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="publications")
 */
class Publication implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $description;

    /**
     * @ManyToOne(targetEntity="App\Entities\Application")
     * @JoinColumn(name="application_id", referencedColumnName="id")
     */
    private $application;

    /**
     * @ManyToOne(targetEntity="App\Entities\Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    public function getId(): string
    {
        return $this->id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function fromArray(array $data): void
    {
        $this->setTitle($data['title']);
        $this->setDescription($data['description']);
        $this->setCategory($data['category']);
        $this->setApplication($data['application']);
        $this->setBody($data['body']);
    }

    public function toArray(): array
    {
        return [
            'title'       => $this->getTitle(),
            'description' => $this->getDescription(),
            'category'    => $this->getCategory(),
            'application' => $this->getApplication(),
            'body'        => $this->getBody()
        ];
    }
}