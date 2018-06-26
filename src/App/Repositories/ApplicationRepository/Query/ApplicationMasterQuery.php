<?php

namespace App\Repositories\ApplicationRepository\Query;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Application;
use App\Exceptions\EntityNotFoundException;

final class ApplicationMasterQuery implements ApplicationQueryInterface
{
    /**
     * The Application entity repository
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Application::class);
    }

    /**
     * Get any application by slug
     * @method getApplication
     * @param string $appName
     * @return Application
     */
    public function getApplication(string $appName): Application
    {
        $application = $this->repository
            ->findOneBy([
                'slug' => $appName
            ]);

        if (!$application) {
            throw new EntityNotFoundException('Application');
        }

        return $application;
    }

    /**
     * Get all the applications
     * @method getUserApplications
     * @return array
     */
    public function getUserApplications(): array
    {
        $applications = $this->repository
            ->findAll();

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