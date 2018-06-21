<?php

namespace App\Controllers\ApplicationsController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Factorys\Application\Query\ApplicationQueryFactoryInterface as ApplicationQueryFactory;
use App\Exceptions\EntityNotFoundException;

final class AppGetController
{
    /**
     * The factory for the application query repository
     * @var ApplicationQueryFactory
     */
    private $applicationQueryFactory;

    public function __construct(ApplicationQueryFactory $applicationQueryFactory)
    {
        $this->applicationQueryFactory = $applicationQueryFactory;
    }

    public function get(Request $request, Response $response, array $args)
    {
        try {
            $application = $this->applicationQueryFactory
                ->getApplicationQuery()
                ->getApplication($args['app']);

            $response
                ->getBody()
                ->write(json_encode(['app' => $application->toArray()]));

            return $response->withStatus(200);
        } catch (EntityNotFoundException $e) {
            $response
                ->getBody()
                ->write('The application could not be found');

            return $response->withStatus(404);
        }
    }
}