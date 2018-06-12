<?php

namespace App\Factorys\Application\Query;

use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface;
use App\Services\Player;
use Psr\Container\ContainerInterface;
use App\Repositories\ApplicationRepository\Query\ApplicationQuery;
use App\Repositories\ApplicationRepository\Query\ApplicationMasterQuery;
use App\Exceptions\UserTypeNotFoundException;
use App\Repositories\ApplicationRepository\Query\ApplicationTeamQuery;

final class ApplicationQueryFactory implements ApplicationQueryFactoryInterface
{
    /**
     * The dependency container
     * @var ContainerInterface
     */
    private $dependecyContainer;

    public function __construct(ContainerInterface $dependecyContainer)
    {
        $this->dependecyContainer = $dependecyContainer;
    }

    public function getApplicationQuery(): ApplicationQueryInterface
    {
        $user = Player::user();

        switch ($user->getType()) {
            case 'P':
                return $this->dependecyContainer->get(ApplicationQuery::class);
            case 'M':
                return $this->dependecyContainer->get(ApplicationMasterQuery::class);
            case 'W':
                return $this->dependecyContainer->get(ApplicationTeamQuery::class);
        }
    }
}