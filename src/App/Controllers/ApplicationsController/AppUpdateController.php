<?php

namespace App\Controllers\ApplicationsController;

use App\Repositories\ApplicationRepository\Update\ApplicationUpdateInterface as ApplicationUpdate;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AppUpdateController
{
    /**
     * The application repository for update operaion
     * @var ApplicationUpdate
     */
    private $appUpdate;

    public function __construct(ApplicationUpdate $appUpdate)
    {
        $this->appUpdate = $appUpdate;
    }
    
    public function update(Request $request, Response $response, array $args)
    {
        $appUpdated = $this->appUpdate->update($args['app'], $request->getParsedBody());

        if ($appUpdated) {
            $response
                ->getBody()
                ->write('The app was successfully updated');

            return $response
                ->withStatus(200);
        }

        $response
            ->getBody()
            ->write('The app was not updated');

        return $response
            ->withStatus(403);
    }
}