<?php

namespace App\Repositories\ApplicationRepository\Query;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Application;
use App\Services\Player;

final class ApplicationTeamQuery implements ApplicationQueryInterface
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

    public function getApplication(string $appName): Application
    {
        $writter = Player::user();

        $application = $this->repository
            ->createQueryBuilder('a')
            ->innerJoin('a.team', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $writter->getId())
            ->getQuery()
            ->getSingleResult();

        return $application;
    }

    public function getUserApplications(): array
    {
        $writter = Player::user();

        $applications = $writter->getApplications()
            ->toArray();

        return array_map(function (Application $application) {
            return [
                'name'        => $application->getName(),
                'description' => $application->getDescription(),
                'url'         => $application->getUrl()
            ];
        }, $applications);
    }
}