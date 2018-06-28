<?php

namespace App\Controllers\ApplicationsController;

use App\Repositories\ApplicationRepository\Destruction\ApplicationDestructionInterface as ApplicationDestruction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\EntityNotFoundException;

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
        try {
            $appDeleted = $this->appDeleteRepository->destroy($args['app']);

            $response->getBody()->write('The app was successfully deleted');

            return $response
                ->withStatus(200);
        } catch (EntityNotFoundException $e) {
            $response->getBody()->write('Unsuccessful information to complete the operation');

            return $response
                ->withStatus(404);
        }
    }
}