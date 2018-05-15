<?php

namespace App\Repositories\ApplicationRepository\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Entities\Application;
use App\Services\Player;
use App\Database\Types\ApplicationType;

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

    /**
     * @inheritdoc
     */
    public function getApplication(string $appName): ?Application
    {
        $user = Player::user();

        return $this->repository
            ->findOneBy([
                'owner' => $user->getId(),
                'name' => $appName
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getUserApplications(): array
    {
        $user = Player::user();

        $applications = $this->repository
            ->findBy(['owner' => $user->getId()]);

        $types = ApplicationType::getApplicationTypes();

        return array_map(function (Application $application) use ($types) {
            return [
                'name'        => $application->getName(),
                'description' => $application->getDescription(),
                'type'        => $types[$application->getType()],
                'url'         => $application->getUrl()
            ];
        }, $applications);
    }
}