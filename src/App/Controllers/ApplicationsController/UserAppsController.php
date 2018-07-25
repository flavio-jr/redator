<?php

namespace App\Controllers\ApplicationsController;

use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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

        $response
            ->getBody()
            ->write(json_encode(['apps' => $apps]));

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}