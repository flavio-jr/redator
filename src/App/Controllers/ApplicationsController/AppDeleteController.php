<?php

namespace App\Controllers\ApplicationsController;

use App\Repositories\ApplicationRepository\Destruction\ApplicationDestructionInterface as ApplicationDestruction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AppDeleteController
{
    /**
     * The application delete repository
     * @var ApplicationDestruction
     */
    private $appDeleteRepository;

    public function __construct(ApplicationDestruction $appDeleteRepository)
    {
        $this->appDeleteRepository = $appDeleteRepository;
    }

    public function delete(Request $request, Response $response, array $args)
    {
        $appDeleted = $this->appDeleteRepository->destroy($args['app']);

        if ($appDeleted) {
            $response->getBody()->write('The app was successfully deleted');

            return $response
                ->withStatus(200);
        }
        
        $response->getBody()->write('You are not allowed to do the operation');

        return $response
            ->withStatus(403);
    }
}