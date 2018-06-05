<?php

namespace App\Controllers\ApplicationsController;

use App\Repositories\ApplicationRepository\Store\ApplicationStoreInterface as ApplicationStore;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class AppStoreController
{
    /**
     * The application store repository
     * @var ApplicationStore
     */
    private $applicationStore;

    public function __construct(ApplicationStore $applicationStore)
    {
        $this->applicationStore = $applicationStore;
    }

    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $application = $this->applicationStore->store($data);

            $response->getBody()->write(json_encode(['app' => $application->toArray()]));

            return $response->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            $response->getBody()->write('The app name is already taken');

            return $response->withStatus(412);
        }
    }
}