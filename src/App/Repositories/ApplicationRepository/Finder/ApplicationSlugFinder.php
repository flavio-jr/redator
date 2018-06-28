<?php

namespace App\Repositories\ApplicationRepository\Finder;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Application;
use App\Exceptions\EntityNotFoundException;

final class ApplicationSlugFinder implements ApplicationFinderInterface
{
    /**
     * The application entity repository
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Application::class);
    }

    public function find(string $identifier): Application
    {
        $application = $this->repository
            ->findOneBy(['slug' => $identifier]);

        if (!$application) {
            throw new EntityNotFoundException('Application');
        }

        return $application;
    }
}