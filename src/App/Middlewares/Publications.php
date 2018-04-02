<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Repositories\ApplicationRepository;

final class Publications
{
    /**
     * The user session service
     * @var ApplicationRepository
     */
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * Check user identity to authorize or not
     * @param Request $request
     * @param Response $response
     * @param callback $next
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $route = $request->getAttribute('route');
        $applicationId = $route->getArgument('application_id');

        $application = $this->applicationRepository->find($applicationId);

        if ($this->applicationRepository->appBelongsToUser($application)) {
            return $next($request, $response);
        }

        return $response->write(json_encode(['error' => 'App doesn\'t belongs to user']))->withStatus(403);
    }
}