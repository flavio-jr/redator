<?php

namespace App\Controllers\ApplicationsController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\ApplicationRepository\OwnershipUpdate\ApplicationOwnershipTransferInterface as ApplicationOwnershipTransfer;
use App\Exceptions\UserNotAllowedReceiveApplicationOwnershipTransfer;

final class AppOwnershipTransferController
{
    /**
     * The repository for transfer applications ownerships
     * @var ApplicationOwnershipTransfer
     */
    private $applicationOwnershipTransfer;

    public function __construct(ApplicationOwnershipTransfer $applicationOwnershipTransfer)
    {
        $this->applicationOwnershipTransfer = $applicationOwnershipTransfer;
    }

    public function transferOwnership(Request $request, Response $response, array $args)
    {
        try {
            $newOwnerUsername = $request->getParsedBody()['new_owner'];
            $applicationName = $args['app'];

            $this->applicationOwnershipTransfer
                ->transferOwnership($applicationName, $newOwnerUsername);

            $response
                ->getBody()
                ->write('The application ownership was successfully transfered');

            return $response->withStatus(200);
        } catch (UserNotAllowedReceiveApplicationOwnershipTransfer $e) {
            $response
                ->getBody()
                ->write('The new owner cant receive the application ownership');

            return $response->withStatus(403);
        }
    }
}