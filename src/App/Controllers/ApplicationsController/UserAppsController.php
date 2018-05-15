<?php

namespace App\Controllers\ApplicationsController;

use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use Slim\Http\Request;
use Slim\Http\Response;

final class UserAppsController
{
    /**
     * The application query repository
     * @var ApplicationQuery
     */
    private $applicationQuery;

    public function __construct(ApplicationQuery $applicationQuery)
    {
        $this->applicationQuery = $applicationQuery;
    }

    public function get(Request $request, Response $response)
    {
        $apps = $this->applicationQuery->getUserApplications();

        return $response
            ->write(json_encode(['apps' => $apps]))
            ->withStatus(200);
    }
}