<?php

namespace App\Controllers\ApplicationsController;

use App\Repositories\ApplicationRepository\Store\ApplicationStoreInterface as ApplicationStore;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
        $data = $request->getParsedBody();

        $application = $this->applicationStore->store($data);

        return $response
            ->withStatus(200)
            ->getBody()
            ->write(json_encode(['app' => $application]));
    }
}