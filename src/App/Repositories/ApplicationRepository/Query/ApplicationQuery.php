<?php

namespace App\Repositories\ApplicationRepository\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Entities\Application;
use App\Services\Player;

final class ApplicationQuery implements ApplicationQueryInterface
{
    /**
     * The repository for Application entity
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Application::class);
    }

    public function getApplication(string $appName): ?Application
    {
        $user = Player::user();

        return $this->repository
            ->findOneBy([
                'owner' => $user->getId(),
                'name' => $appName
            ]);
    }
}